<?php
use Elliptic\EC;
use Web3\Contract;
use kornrunner\Keccak;
use App\Models\Register; 
use App\Models\Item; 
use App\Models\User; 
use App\Models\Bidder;
use App\Models\Likes; 

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
    return Item::orderBy('points', 'desc')->take(6)->get(); 
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
    return Item::where('status', 0)->take(8)->get(); 
}

function completed()
{
    return Item::where('status', '>', 1)->take(8)->get(); 
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

function startBid()
{
    $items = Item::where('status', 0)->oldest();
    if( $items->exists() ){
        foreach( $items->get() as $item ){
            if( now()->greaterThanOrEqualTo($item->start_time) ){
                $item->startBid();
            }
        }
    }
}

function get_abi()
{
    return file_get_contents('../resources/js/Bidding_ABI.json'); 
}

function getWinner($id)
{
    $contract = new Contract(env('NODE_ENDPOINT'), get_abi());
    $result =  ""; 
    try{
        $contract->at('0x3733104daE61D6dA13aF964449bB41F9d6C5FE3c')->call('items', $id, function($error, $data) use(&$result){
            $result = $data;
        });
    }catch(\Exception $e){
        $result = $e->getMessage(); 
    }
    if(gettype($result) != 'string')
        $winner = $result['winner']; 
    else 
        $winner = getWinner($id); 

    $bidder = Bidder::where([
        ['item_id', $id],
        ['address', $winner]
    ])->first();

    if($bidder){
        $bidder->winner = 1; 
        $bidder->save(); 
    }
    return $winner; 
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