<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Bidder;

class AuctionController extends Controller
{

    public function live()
    {
        return view('auction.live'); 
    }

    public function upcoming()
    {
        return view('auction.upcoming');
    }

    public function completed()
    {
        return view('auction.completed');
    }

    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('auction.show', compact('item')); 
    }

    public function place_bid(Request $request)
    {
        $id = $request->input('id'); 
        $check = $request->input('check');
        $amt = (int)$request->input('amt'); 

        $user = Auth::user(); 
        $item = Item::find($id);

        if($item->status == 1)
            return ['error'=>"Bidding already started!"];
        else if($item->status > 1)
            return ['error'=>"Bidding has ended!"];

        if($check){
            if( $amt < $item->min_bid )
                return ['error'=>"Min bid is ".$item->min_bid.' credits']; 
            if( $user->bid_credit < $amt )
                return ['error'=>"Insufficient bid credit"]; 
            
            return ['done'=>true]; 
        }
        return $user->placeBid($item, $amt); 
    }

    public function claim_winner(Request $request)
    {
        $id = $request->input('id'); 
        $hash = $request->input('hash'); 
        $item = Item::find($id);
        
        $bidder = Bidder::find($item->bidder_id); 
        $bidder->hash = $hash; 
        $bidder->save();  

        $item->status = 3;
        $item->save(); 
        return ['done'=>true]; 
    }





    // buy credit
    public function buy_credit()
    {
        return view('buy_credit'); 
    }

    public function credit_point(Request $request)
    {
        $amt = (int)$request->input('ffffxfr'); 
        $user = Auth::user(); 
        $user->creditBid($amt);  
        return ['done'=>true]; 
    }
}
