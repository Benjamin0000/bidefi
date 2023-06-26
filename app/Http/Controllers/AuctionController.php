<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\BidEvent; 
use App\Models\Item;
use App\Models\Bidder;
use App\Models\Likes; 

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
        $bidders = Bidder::where('item_id', $item->id)->orderBy('updated_at', 'desc')->take(10)->get(); 
        return view('auction.show', compact('item', 'bidders')); 
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

            $used = get_used($user->id, $item->id); 
            if($used <= $item->free_bid)
                $user->bid_credit += ($item->free_bid - $used); 

            if($user->bid_credit  < $amt )
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

    public function like(Request $request)
    {
        $id = $request->input('id');
        $user = Auth::user();
        $item = Item::find($id); 

        if(!$item || !$user) return ; 

        $like = Likes::where([ 
            ['user_id', $user->id],
            ['item_id', $id]
        ])->first();
        
        if($like){
            $like->delete(); 
            $item->likes -= 1;
        }else{
            Likes::create([
                'user_id'=>$user->id,
                'item_id'=>$id
            ]); 
            $item->likes += 1;
        }
        $item->save(); 
    }

    public function add_views(Request $request)
    {
        $id = $request->input('id');
        $item = Item::find($id); 
        if(!$item) return ; 
        $item->views += 1; 
        $item->save(); 
    }
}
