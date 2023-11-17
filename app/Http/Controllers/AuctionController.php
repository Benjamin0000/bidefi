<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\BidEvent; 
use App\Models\Item;
use App\Models\Bidder;
use App\Models\Likes; 
use App\Models\BidHistory; 
use App\Models\Winner;

class AuctionController extends Controller
{

    public function live()
    {
        $items = live_auction(); 
        return view('auction.live', compact('items')); 
    }

    public function load_more($type)
    {
        switch($type){
            case '1': //live
                $items = live_auction();
                break; 
            case '2':  //upcoming
                $items = upcoming(); 
                break; 
            case '3':  //completed
                $items = completed(); 
                break; 
            default: 
                return; 
        }
        $views = view('auction.component.item', ['auctions'=>$items]); 
        return [
            'view'=>"$views",
            'count'=> $items->count()
        ]; 
    }

    public function upcoming()
    {
        $items = upcoming(); 
        return view('auction.upcoming', compact('items'));
    }

    public function completed()
    {
        $items = completed(); 
        return view('auction.completed', compact('items'));
    }

    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $bidders = Bidder::where('item_id', $item->id)->orderBy('updated_at', 'desc')->take(10)->get(); 
        $items = Item::where([ ['network', $item->network], ['status', 0] ])->latest()->take(8)->get(); 
       
        if($credId = request()->gal) // getting the credential id
            session(["credId$item_id" => $credId]);
        return view('auction.show', compact('item', 'bidders', 'items')); 
    }

    public function claim_winner(Request $request)
    {
        $id = $request->input('id'); 
        $hash = $request->input('hash'); 
        $item = Item::find($id);
        $user = Auth::user(); 
        if(!$user) return; 
        if($item->canClaim()){
            $bidder = Bidder::where([ ['item_id', $id], ['user_id', $user->id] ])->first();
            $bidder->hash = $hash; 
            $bidder->winner = 1;
            $bidder->save();

            if($item->share > 0){
                $winner = Winner::where([ ['user_id', $user->id], ['item_id', $id], ['status', 0] ])->first(); 
                $winner->status = 1;
                $winner->save(); 
                $winners = Winner::where([ ['item_id', $id], ['status', 1] ])->count();
                if($winners >= $item->share)
                    $item->status = 3;
            }else{
                $item->status = 3;
            }
            $item->save(); 
        }
        return ['done'=>true]; 
    }

    public function generate_bid_token(Request $request)
    {
        if (!$request->ajax()) return;
        $id = $request->input('id'); 
        if( !Item::where([ ['id', $id], ['status', '<', 2] ])->first() ) return ;
        return ["token" => generate_bid_secrete($id)];
    }

    // buy credit
    public function buy_credit()
    {
        $user = Auth::user(); 
        if(!$user)
            return redirect('/');
        return view('buy_credit', compact('user')); 
    }

    public function credit_bid(Request $request)
    { 
        if (!$request->ajax()) return;
        $token = $request->input('ffffxfr'); 
        $fee = $request->input('fee'); 
        $user = Auth::user();
        $check = BidHistory::where([ ['user_id', $user->id], ['secrete', $token], ['status', 0] ])->first();
 
        if ($check) {
            if ($check->trial >= 5) return ['stop' => "don't call again"];
            $item = $check->item; 
            $confirm = confirm_bid($user->address, $item->id, $item->network);

            if (isset($confirm['secrete'])) {
                $points = hexdec($confirm['points']->toHex());
                $time = hexdec($confirm['time']->toHex());
                $check->amt = $points;
                $check->time = $time;
                $check->status = 1; 
                $check->save();
                $user->placeBid($item, $points);  
                increase_fee($fee, $item->network); 
                set_galxe($item->id, $user->address);
                return ["done" => true]; 
            } else {
                $check->trial += 1;
                $check->save();
                return ["trial" => "not found"];
            }
        }else{
            return ["error" => true]; 
        }
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

    public function store_total_credit_bought(Request $request)
    {
        if($user = Auth::user()){
            $points = (int)$request->input('amt');  
            increase_total_credits($points); 
        }
    }
}
    