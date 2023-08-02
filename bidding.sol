// SPDX-License-Identifier: GPL-3.0
pragma solidity ^0.8.19;
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/IERC1155.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC721/IERC721.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC20/IERC20.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/utils/ERC1155Holder.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC721/utils/ERC721Holder.sol";
 
contract Bidding is ERC1155Holder, ERC721Holder{

    struct Item{
        uint id; //nft id
        address Address; // contract
        uint8 _type; //erc721 //erc1155 || erc20 || native token
        uint startTime;
        bool status; // 0||1 claimed or not
        mapping(address=>uint) bid_credit; //spent by the bidder
        uint points; // total point lodged 
        uint reqPoints; //required points 
        address winner; // the winner 
        uint lastPoint; // last point lodged
        uint token_amount; // erc20 || native token
        uint free_credit;
        uint exp_time; 
    }
    //bidding parameters
    struct Params{
        uint total; // item tracker.
        uint sec;
        uint pointPrice; // cost of bid points
        address admin;
    }

    mapping(address=>uint) public points; // users point balance.
    mapping(uint=>Item) public items; //items
    mapping(address=>bool) public admin; 

    Params public ItemData;

    modifier onlyOwner {
        require(msg.sender == ItemData.admin);
        _;
    }

    constructor(){ 
        ItemData.admin = msg.sender;
        ItemData.sec = 15;
        ItemData.pointPrice = 0.001 ether;
    }   

    function listItem(
        uint _id,
        uint token_amount, 
        uint8 _type,
        address _address,
        uint startTime,
        uint _free_credit,
        uint _reqPoints
    ) public {
        require( msg.sender == ItemData.admin || admin[msg.sender] == true, "unauthorized");
        items[ItemData.total].id = _id;
        items[ItemData.total].token_amount = token_amount;
        items[ItemData.total]._type = _type;
        items[ItemData.total].Address = _address;
        items[ItemData.total].free_credit = _free_credit;
        items[ItemData.total].startTime = startTime;  
        items[ItemData.total].reqPoints = _reqPoints; 
        ItemData.total+=1;
    }

    function buyPoint(uint _points) public payable {
        uint cost = _points * ItemData.pointPrice;
        require(msg.value >= cost, "insufficient"); 
        unchecked{
            points[msg.sender] += _points;
        }
    }
    
    function placeBid(uint id, uint _points) public {
        Item storage item = items[id];
        if(item.exp_time > 0)
            require(block.timestamp < item.exp_time, 'Bidding ended');
        
        uint balance = points[msg.sender];
        uint used_credit = item.bid_credit[msg.sender]; //already alotted point
        uint bonus = item.free_credit;
        bool free = used_credit < bonus; 

        if( free ){
            unchecked{
                balance += ( bonus - used_credit );
            }
        }
        require( balance >= _points, "Insufficient for bid");

        unchecked{
            uint cost = _points;
            if( free ){
                uint free_credit = bonus - used_credit;
                if( cost >= free_credit )
                    cost -= free_credit;
                else 
                    cost = 0; 
            }
            points[msg.sender] -= cost; //reducing users point
            item.points += _points;
            item.bid_credit[msg.sender] += _points;
        }

        uint bid = item.bid_credit[msg.sender];
        if( bid >= item.lastPoint ){
            item.winner = msg.sender;
            item.lastPoint = bid;
        }
        if(item.points >= item.reqPoints){
            if(item.exp_time == 0){
                uint sec = ItemData.sec * item.points;  
                unchecked{
                    item.exp_time = block.timestamp + (item.startTime  * 60) + sec; 
                }
            }else{
                unchecked{
                    item.exp_time += ItemData.sec * _points;
                }
            }
        }
    }

    function claimPrice(uint id) public {
        Item storage item = items[id];
        uint8 _type = item._type; 

        require(!item.status, "Already claimed");
        require(item.exp_time > 0 && block.timestamp >= item.exp_time, 'Bidding has not ended');
        require( msg.sender == item.winner, "Invalid winner");
        item.status = true;

        if(_type == 1){
            sendERC721(item, msg.sender);
        }else if(_type == 2){
            sendERC1155(item, msg.sender);
        }else if(_type == 3){ // erc20 token
            IERC20(item.Address).transferFrom(address(this), msg.sender, item.token_amount);
        }else if(_type == 4){ //native token
            (bool success, ) =  payable(msg.sender).call{value: item.token_amount}("");
            require(success, "Failed to send Ether");
        }
    } 

    function sendERC721(Item storage item, address to) private {
        IERC721(item.Address).transferFrom(address(this), to, item.id);
    }

    function sendERC1155(Item storage item, address to) private {
        IERC1155(item.Address).safeTransferFrom(address(this), to, item.id, 1, "");
    }

    function changeSeconds(uint16 _sec) public onlyOwner {
        ItemData.sec = _sec;
    }

    function changePointPrice(uint _price) public onlyOwner {
        ItemData.pointPrice = _price;
    }

    function moveERC721(address _address, uint _id) public onlyOwner{
        IERC721(_address).transferFrom(address(this), ItemData.admin, _id);
    }

    function moveERC1155( address _address, uint[] memory _id, uint[] memory _amt) public onlyOwner {
        IERC1155(_address).safeBatchTransferFrom(address(this), ItemData.admin, _id, _amt, "");
    }

    function approveAdmin(address _address, bool _type) public onlyOwner{
        admin[_address] = _type; 
    }

    function withdraw(uint amt) public onlyOwner {
        (bool success, ) =  payable(msg.sender).call{value: amt}("");
        require(success, "Failed to send Ether");
    }
    
}