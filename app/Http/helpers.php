<?php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Elliptic\EC;
use Web3\Contract;
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use kornrunner\Keccak;
use App\Models\Register; 
use App\Models\Item; 
use App\Models\User; 
use App\Models\Bidder;
use App\Models\Likes; 
use App\Models\BidHistory; 
use App\Models\Winner; 

function get_network_name($id)
{
    $name = ""; 

    if($id == 324)
        $name = "zkSync Era";
    elseif($id == 59144)
        $name = "Linea";
    elseif($id == 8453)
        $name = "Base";
    elseif($id == 56)
        $name = "BSC"; 
    elseif($id == 42161)
        $name = "Arbitrum One";
    elseif($id == 10)
        $name = "OP Mainnet"; 
    elseif($id == 97)
        $name = "BSC Testnet";

    return $name; 
}

function get_logo($id)
{
    $logo = ""; 
    if($id == 324)
        $logo = "/icon/zksync.png";
    elseif($id == 59144)
        $logo = "/icon/linea.png";
    elseif($id == 8453)
        $logo = "/icon/base.png";
    elseif($id == 56)
        $logo = "/icon/bnb.png";
    elseif($id == 42161)
        $logo = "/icon/arbitrum.png";
    elseif($id == 10)
        $logo = "/icon/optimism.png"; 
    elseif($id == 97)
        $logo = "/icon/bnb.png";

    return $logo;
}

function get_end_points($id)
{
    $url = ""; 
    if($id == 324)
        $url = "https://mainnet.era.zksync.io";
    elseif($id == 59144)
        $url = "https://linea-mainnet.infura.io/v3/".env('INFURA_KEY');
    elseif($id == 8453)
        $url = "https://base-mainnet.blastapi.io/".env('BLAST_KEY');
    elseif($id == 56)
        $url = "https://bsc-mainnet.blastapi.io/".env('BLAST_KEY'); 
    elseif($id == 42161)
        $url = "https://arbitrum-one.blastapi.io/".env('BLAST_KEY');
    elseif($id == 10)
        $url = "https://optimism-mainnet.blastapi.io/".env('BLAST_KEY'); 
    elseif($id == 97)
        $url = "https://bsc-testnet.blastapi.io/".env('BLAST_KEY');
    return $url;     
}

function get_contract_adress($id)
{
    $address = "";

    if($id == 324)
        $address = "0x4d73cDFF03C4Cf245Bc203374B83f4c43a292bfC";
    elseif($id == 59144)
        $address = "0x88842fa0Af9266cfAe10B7470A9A80384195746c";
    elseif($id == 8453)
        $address = "0x88842fa0Af9266cfAe10B7470A9A80384195746c";
    elseif($id == 56)
        $address = "0x1EE4CC90e11a42635C1e7829Aa08d5e3FC5eDe8C"; 
    elseif($id == 42161)
        $address = "0x88842fa0Af9266cfAe10B7470A9A80384195746c";
    elseif($id == 10)
        $address = "0x88842fa0Af9266cfAe10B7470A9A80384195746c"; 
    elseif($id == 97)
        $address = "0xb0634bb4857bab45ac4fc440fee6e715824a96ef"; 

    return $address;
}

function generateRefCode()
{
    $code = bin2hex(openssl_random_pseudo_bytes(5));
    if( User::where('ref_id', $code)->exists() )
        return generateRefCode();
    return $code;
}

function get_pct($min, $max)
{
    return round($min/$max *100); 
}

function cal_pct($total, $pct)
{
    return ($pct / 100) * $total; 
}

function query_price($name, $reg_name)
{
    try{
        $data = Http::get('https://api.coingecko.com/api/v3/coins/'.$name);
        $price = isset($data['market_data']) && 
        isset($data['market_data']['current_price']) && 
        isset($data['market_data']['current_price']['usd']) ? 
        $data['market_data']['current_price']['usd'] : 0;
        $reg = Register::where('name', $reg_name)->first();
    
        if($price){
            if(!$reg){
               $reg = new Register();
               $reg->name = $reg_name;
            }
            $reg->value = $price;
            $reg->save();
        }
    }catch(Throwable $e){}
}

function setEthPrice() 
{
    query_price('ethereum', 'eth_price'); 
    query_price('binancecoin', 'bnb_price'); 
}

function increase_fee($fee, $network)
{
    $name = get_network_name($network); 
    if($name == 'BSC'){
        $price = (float)get_register('bnb_price'); 
    }else{
        $price = (float)get_register('eth_price'); 
    }
    $amt_usd = $price * $fee; 

    $reg = Register::where('name', 'total_fees')->first();

    if(!$reg){
        $reg = new Register();
        $reg->name = 'total_fees';
        $reg->value = $amt_usd;
    }else{
        $reg->value = (float)$reg->value + $amt_usd;
    }
    $reg->save();
}

function tableNumber( int $total ) : int
{
    if( request()->page && request()->page != 1 )
        return ( request()->page*$total ) - $total + 1;
    return 1;
}

function pubKeyToAddress($pubkey)
{
    return "0x" . substr(Keccak::hash(substr(hex2bin($pubkey->encode("hex")), 1), 256), 24);
}

function verifySignature($message, $signature, $address)
{
    $msglen = strlen($message);
    $hash   = Keccak::hash("\x19Ethereum Signed Message:\n{$msglen}{$message}", 256);
    $sign   = ["r" => substr($signature, 2, 64),
               "s" => substr($signature, 66, 64)];

    $recid  = ord(hex2bin(substr($signature, 130, 2))) - 27;

    if ($recid != ($recid & 1))
        return false;

    $ec = new EC('secp256k1');
    $pubkey = $ec->recoverPubKey($hash, $sign, $recid);

    return strtolower($address) == pubKeyToAddress($pubkey);
}

function set_register($name, $value="")
{
    if( $reg = Register::where('name', $name)->first() ){
        $reg->value = $value; 
        $reg->save(); 
        return ; 
    }
    Register::create([
        'name'=>$name,
        'value'=>$value
    ]); 
}

function get_register($name)
{
    $reg = Register::where('name', $name)->first(); 
    if(!$reg)
        $reg = Register::create(['name'=>$name]); 
    return $reg->value; 
}

function increase_total_credits($amt)
{
    $reg = Register::where('name', 'total_credits')->first(); 
    if(!$reg)
        $reg = Register::create(['name'=>'total_credits']); 
    $reg->value = (int)$reg->value + $amt;
    $reg->save();  
} 

function increase_items()
{
    $reg = Register::where('name', 'total_items')->first(); 
    if(!$reg)
        $reg = Register::create(['name'=>'total_items']); 
    $reg->value = (int)$reg->value + 1;
    $reg->save();  
}

function trendings()
{
    return Item::where([ ['h', 1] , ['status', 0] ])->latest()->take(4)->get(); 
}

function live_auction()
{
    return Item::where('status', 1)->paginate(8);
}

function top_bidders()
{
    return User::where('total_credit', '>', 0)
            ->orderBy('total_credit', 'desc')->take(9)->get(); 
}

function upcoming()
{
    return Item::where('status', 0)->latest()->paginate(8);
}

function completed()
{
    return Item::where('status', '>', 1)->paginate(8); 
}

function latest_winners()
{
   return Bidder::where('winner', 1)->distinct('user_id')
   ->latest()->take(10)->get(); 
}

function truncateAddress($text)
{
    $textLength = strlen($text);
    $maxChars = 10;
    return substr_replace($text, '...', $maxChars/2, $textLength-$maxChars);
}

function get_bidder($bidder_id)
{
    $name = ""; 
    if( $bidder =  Bidder::find($bidder_id) ){
        if( $user = User::find($bidder->user_id) ){
            if($user->fname || $user->lname)
                $name = $user->fname." ".$user->lname;  
            else 
                $name = truncateAddress($user->address); 
        }
    }
   return $name; 
}

function eth_to_usd($token)
{
    $price = (float)get_register('eth_price'); 
    return $price * $token; 
}

function opos($val) : int
{
    if($val == 0) return 1;
    return 0;
}



function get_abi()
{
    if( !$file = @file_get_contents('../resources/js/Bidding_ABI.json') ){
        
        if( !$file = @file_get_contents('resources/js/Bidding_ABI.json') ){
            $file = file_get_contents('/var/www/bidefi.io/resources/js/Bidding_ABI.json'); 
        }
    }
        
    return $file; 
}

function liked($id, $user_id)
{
    return Likes::where([
        ['item_id', $id],
        ['user_id', $user_id]
    ])->exists(); 
}

function get_bid_value($amt)
{
    return $amt * (float)get_register('bid_price'); 
}

function get_used($user_id, $item_id)
{
    $bidder = Bidder::where([
        ['user_id', $user_id],
        ['item_id', $item_id]
    ])->first(); 

    if(!$bidder) return 0; 
    return $bidder->points; 
}

function generateNumber()
{
    return random_int(12345678, 984848948778474).time(); 
}

function generate_bid_secrete($id)
{
    $user = Auth::user(); 
    $bid = BidHistory::where([ ['item_id', $id], ['user_id', $user->id], ['status', 0] ])->first(); 
    if(!$bid){
        $bid = BidHistory::create([ 
            'item_id'=>$id,
            'user_id'=>$user->id,
            'secrete'=>generateNumber()
        ]); 
    }
    return $bid['secrete']; 
}

function confirm_bid($address, $id, $network)
{
    $contract = new Contract(get_end_points($network), get_abi());
    $result =  ""; 
    try{
        $contract->at(get_contract_adress($network))->call('bid_entered', $address, $id, function($error, $data) use(&$result){
            $result = $data;
        });
    }catch(\Exception $e){
        $result = $e->getMessage(); 
    }
    if(gettype($result) == 'string')
        return confirm_bid($address, $id, $network); 
    return $result; 
} 


function getWinner($id, $network)
{ 
    $contract = new Contract(get_end_points($network), get_abi());
    $result =  ""; 
    try{ 
        $contract->at(get_contract_adress($network))->call('items', $id, function($error, $data) use(&$result){
            $result = $data;
        });
    }catch(\Exception $e){
        $result = $e->getMessage(); 
    }
    if(gettype($result) != 'string')
        $winner = $result['winner']; 
    else 
        return getWinner($id); 

    $bidder = Bidder::where([
        ['item_id', $id],
        ['address', $winner]
    ])->first();

    $item = Item::find($id);

    if( $item && $bidder ){
        $item->bidder_id = $bidder->id; 
        $item->save(); 
    }

    if($bidder){
        $bidder->winner = 1; 
        $bidder->save(); 
    }
    return $winner; 
}

function getTheWinner($id)
{
    if($item = Item::find($id)){
        $bidder = Bidder::where('item_id', $id)->orderBy('points', 'desc')->latest()->first();
        return $bidder ?: ''; 
    }
    return; 
}

function set_winners($id)
{
    $item = Item::find($id);
    if(!$item) return; 
    $bidders = Bidder::where('item_id', $id)->orderBy('points', 'desc')->latest()->take($item->share)->get();
    foreach($bidders as $bidder){
        Winner::create([
            'user_id'=>$bidder->user_id,
            'item_id'=>$item->id
        ]); 
        $bidder->winner = 1;
        $bidder->save(); 
    }
}