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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function bid(Item $item)
    {
        $item->used += 1;
        $item->timer = now()->addSeconds(15);
        // $item->sec = mt_rand(7, $item->bid_sec);
        $item->bid_price = $item->used * $item->start_price;
        $item->bidder_id = $this->id; 
        $item->save();

        $this->used += 1;
        $this->switch = opos($this->switch);
        $this->save();

        $bidder = view('bidder', compact('item'));
        $bidder2 = view('bidder2', compact('item'));
        $data = [ 
            'id'=>$item->id,
            'bidder'=>"$bidder",
            'bidder2'=>"$bidder2",
            'timer'=>$item->timer,
            'bid_price'=>number_format($item->bid_price, 2),
            'bid_price_usd'=>number_format(eth_to_usd($item->bid_price), 2),
            'status'=>$item->status,
            'type'=>'bid'
        ];
        BidEvent::dispatch($data);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id'); 
    }


}
