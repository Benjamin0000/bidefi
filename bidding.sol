// SPDX-License-Identifier: GPL-3.0
pragma solidity ^0.8.19;
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/IERC1155.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC721/IERC721.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC20/IERC20.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/utils/ERC1155Holder.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC721/utils/ERC721Holder.sol";

contract Bidding is ERC1155Holder, ERC721Holder{

    event NewBid(address indexed _from, uint indexed _id, Bid _value);

    struct Item{ 
        uint id; //nft id
        address Address; // contract
        uint8 _type; //erc721 //erc1155 || erc20 || native token
        uint share;
        uint claimed; 
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
        uint16 sec;
        uint pct; 
        uint pointPrice; // cost of bid points
        uint bid_fee; 
        address admin;
    }

    struct Bid{
        uint secrete; 
        uint points; 
        uint time; 
    }

    mapping(address=>mapping(uint=>Bid)) public bid_entered; 
    mapping(address=>uint) public points; // users point balance.
    mapping(uint=>Item) public items; //items
    mapping(address=>bool) public admins; 
    mapping(uint=>mapping(address=>bool)) public claimed; 
    mapping(uint=>address[]) public winners;
    mapping(uint=>uint[]) public bid_points;
 
    Params public ItemData;
    modifier onlyOwner {
        require(msg.sender == ItemData.admin);
        _;
    }

    constructor(address _admin, uint16 sec, uint price){
        ItemData.admin = _admin;
        ItemData.sec = sec;
        ItemData.pointPrice = price;
    }       

    function listItem(
        uint id, 
        uint _id,
        uint token_amount, 
        uint8 _type,
        address _address,
        uint startTime,
        uint _free_credit,
        uint _reqPoints,
        uint share
    ) external {
        require( msg.sender == ItemData.admin || admins[msg.sender] == true, "unauthorized");
        ItemData.total+=1;
        items[id].id = _id;
        items[id].token_amount = token_amount;
        items[id]._type = _type;
        items[id].Address = _address;
        items[id].free_credit = _free_credit;
        items[id].startTime = startTime;  
        items[id].reqPoints = _reqPoints; 
        items[id].share = share; 
    }
 
    function buyPoint(uint amt, address _ref) payable external {
        uint cost = amt * ItemData.pointPrice;
        require(msg.value >= cost, "insufficient"); 
        (bool sent, ) = payable(ItemData.admin).call{value: msg.value}("");
        require(sent, "Failed to send Ether");
        require(msg.sender != _ref,  "Invalid Referral"); 

        uint amt2 = ItemData.pct * amt / 100; 
        unchecked{
            points[msg.sender] += amt;
            points[_ref] += amt2;
        }
    }

    function placeBid(uint id, uint _points, uint secrete) public payable {
        Item storage item = items[id];
        if(item.exp_time > 0)
            require(block.timestamp <= item.exp_time, "Bidding ended");

        require(msg.value >= ItemData.bid_fee, "Insufficient Ether to place bid"); 
        (bool sent, ) = payable(ItemData.admin).call{value: msg.value}("");
        require(sent, "Failed to send Ether for fee");

        uint balance = points[msg.sender];
        uint used_credit = item.bid_credit[msg.sender]; //already alotted point
        
        uint bonus = item.free_credit;
        bool free = bonus > 0;
        uint cost = 0;

        if(free){
            require( used_credit + _points <= bonus, "You have exhausted your free bid");
        }else{
            require( balance >= _points, "Insufficient credit for bid");
            cost = _points;
        }
        
        unchecked{
            points[msg.sender] -= cost; //reducing users point
            item.points += _points;
            item.bid_credit[msg.sender] += _points;
        }
        uint bid = item.bid_credit[msg.sender];

        if(item.share > 0){
            uint total = bid_points[id].length; 
            if( total < item.share ){
                bid_points[id].push(_points); 
                winners[id].push(msg.sender);
            }else{
                for(uint i = 0; i < total; i++){
                    if( bid >= bid_points[id][i] ){
                        bid_points[id][i] = bid;
                        winners[id][i] = msg.sender; 
                        break; 
                    }
                }
            }
        }else{
            if( bid >= item.lastPoint ){
                item.winner = msg.sender;
                item.lastPoint = bid;
            }
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
        bid_entered[msg.sender][id].secrete = secrete;
        bid_entered[msg.sender][id].points = _points; 
        bid_entered[msg.sender][id].time = block.timestamp; 
        emit NewBid(msg.sender, id, bid_entered[msg.sender][id]);
    }
     
    function claimPrice(uint id) external {
        Item storage item = items[id];
        uint8 _type = item._type; 
        require(!item.status, "Already claimed");
        require(item.exp_time > 0 && block.timestamp >= item.exp_time, 'Bidding has not ended');

        uint token_amt = item.token_amount;
        address _user = msg.sender; 

        if(item.share > 0){
            bool valid = false; 
            bool _claimed = claimed[id][_user];
            address[] memory _winners = winners[id];
            for(uint i = 0; i < _winners.length; i++){
                if(_winners[i] == _user){
                    valid = true;
                    break; 
                } 
            }
            require(valid , "You are not a valid winner");
            require(!_claimed , "Already claimed your share");

            token_amt /= item.share;
            item.claimed += 1;
            claimed[id][_user] = true; 

            if(item.claimed >= item.share)
                item.status = true;
            
        }else{
            require( _user == item.winner, "Invalid claim");
            item.status = true;
        }
        if(_type == 1){
            IERC721(item.Address).transferFrom(address(this), _user, item.id);
        }else if(_type == 2){
            IERC1155(item.Address).safeTransferFrom(address(this), _user, item.id, token_amt, "");
        }else if(_type == 3){// erc20 token
            IERC20(item.Address).transfer(_user, token_amt);
        }else if(_type == 4){//native token
            (bool success, ) =  payable(_user).call{value: token_amt}("");
            require(success, "Failed to send Ether");
        }
    } 

    function changeSeconds(uint16 _sec) public onlyOwner {
        ItemData.sec = _sec;
    }

    function changePointPrice(uint _price, uint pct, uint bid_fee) public onlyOwner {
        ItemData.pointPrice = _price;
        ItemData.pct = pct;
        ItemData.bid_fee = bid_fee;
    }

    function moveERC721(address _address, uint _id) public onlyOwner {
        IERC721(_address).transferFrom(address(this), ItemData.admin, _id);
    }

    function moveERC1155( address _address, uint[] memory _id, uint[] memory _amt) public onlyOwner {
        IERC1155(_address).safeBatchTransferFrom(address(this), ItemData.admin, _id, _amt, "");
    }

    function approveAdmin(address _address, bool _type) public onlyOwner{
        admins[_address] = _type; 
    }

    function withdraw(uint amt) external onlyOwner {
        require(address(this).balance >= amt, "Insufficient Ether to withdraw"); 
        (bool success, ) =  payable(msg.sender).call{value: amt}("");
        require(success, "Failed to send Ether");
    }

    function point_used(uint _id, address _address) external view returns(uint) {
        return items[_id].bid_credit[_address]; 
    } 
    
}