<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Database\Eloquent\Model;
use App\Events\BidEvent; 
use Carbon\Carbon; 

class Item extends Model
{
    use HasFactory;

    public static function start_bid()
    {
        $items = Self::where('status', 0)->oldest();
        if( $items->exists() ){
            foreach( $items->get() as $item ){
                if( now()->greaterThanOrEqualTo($item->start_time) ){
                    $item->startBid();
                }
            }
        }
    }

    public static function execute_bid()
    {
        $items = Self::where('status', 1)->oldest();
        if( $items->exists() ){
            foreach( $items->get() as $item ){
                $timer = Carbon::parse($item->timer);
                $timer2 = $timer->diffInSeconds(); 
                if($timer2  == 0 || $timer2 > 15){
                    $bidder = Bidder::where([
                        ['item_id', $item->id],
                        ['switch', $item->switch]
                    ])->whereRaw('points > used')->oldest()->first();
    
                    if($bidder)
                        $bidder->bid($item);
                        
                    $item->switchBidders();
                }
                $item->endBid();
            }
        }
    }

    public function switchBidders()
    {
        $check = Bidder::where([
            ['item_id', $this->id],
            ['switch', $this->switch]
        ])->whereRaw('points > used')->oldest()->exists();

        if(!$check){
            $this->switch = opos($this->switch);
            $this->save();
        }
    }

    public function endBid()
    {  
        if( $this->points <= $this->used ){
            $this->status = 2;
            $this->winner = getWinner($this->id);
            $this->save();
            $data = [
                'id'=>$this->id,
                'timer'=>$this->timer,
                'winner'=>$this->winner,
                'type'=>'ended'
            ];
            BidEvent::dispatch($data);
        }
    }

    public function startBid()
    {
        $this->status = 1;
        $this->timer = now()->addSeconds(15);
        $this->save();
        $data = [
            'id'=>$this->id,
            'timer'=>$this->timer,
            'type'=>'started'
        ];
        BidEvent::dispatch($data);
    }

    public function get_last_bidder_avatar()
    {
        if(!$this->bidder_id) return ""; 
        if( $bidder = Bidder::find($this->bidder_id) ){
            if($user = User::find($bidder->user_id)){
                if($user->avatar)
                    return Storage::url($user->avatar); 
            }
                
        }
        return ""; 
    }
}
