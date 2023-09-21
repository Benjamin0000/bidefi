<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Database\Eloquent\Model;
use App\Events\BidEvent; 
use Carbon\Carbon; 

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime'
    ];

    public function the_winner()
    {
        return User::where('address', $this->winner)->first(); 
    }

    public function last_bidder()
    {
        if( $bidder = Bidder::find($this->bidder_id) ){
            return User::find($bidder->user_id); 
        }
    }
    
    public static function start_bid()
    {
        $items = self::where('status', 0)->oldest();
        if( $items->exists() ){
            foreach( $items->get() as $item ){
                if(!$item->start_time){
                    if($item->points >= $item->start_points){
                        $item->start_time = now()->addMinutes($item->ctd_timer); 
                        $item->save(); 
                    }
                }else{
                    if( now()->greaterThanOrEqualTo($item->start_time) ){
                        $item->startBid();
                    }
                }
            }
        }
    }
  
    public static function execute_bid()
    {
        $items = self::where('status', 1)->oldest();
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
            if($this->share > 0){
                set_winners($this->id); 
            }else{
                $this->winner = getTheWinner($this->id)->user->address;
            }
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
                    return $user->avatar; 
            }
                
        }
        return ""; 
    }

    public function canClaim()
    {
        $user = Auth::user();
        if($this->share > 0){
            return Winner::where([ ['user_id', $user->id], ['item_id', $this->id], ['status', 0]  ])->exists(); 
        }elseif($this->winner){
            if(strtolower($this->winner) == strtolower($user->address))
                return true; 
        }else{
            $bidder = Bidder::where('item_id', $this->id)->orderBy('points', 'desc')->first();
            if($bidder && $bidder->user_id == $user->id)
                return true; 
        }
        return false; 
    }
}
