<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use App\Events\BidEvent; 
use Carbon\Carbon;

class Bidder extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'user_id',
        'item_id',
        'address',
        'points'
    ]; 

    public function bid(Item $item)
    { 
        $item->used += 1;
        $item->timer = now()->addSeconds($item->bid_sec);
        // $item->sec = mt_rand(7, $item->bid_sec);
        $item->bid_price = $item->used * $item->start_price;
        $item->save();

        $this->used += 1;
        $this->switch = opos($this->switch);
        $this->save();

       

        $bidders = Self::where('item_id', $item->id)->orderBy('updated_at', 'desc')->take(10)->get();
        $bidders = view('bidders', compact('bidders'));
        $bidders = "$bidders";

        $data = [ 
            'id'=>$item->id,
            'bidders'=>"$bidders",
            'address'=>$this->address,
            'timer'=>Carbon::parse($item->timer)->diffInSeconds(),
            'bid_price'=>$item->bid_price,
            'status'=>$item->status,
            'type'=>'bid'
        ];
        BidEvent::dispatch($data);
    }


}
