<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Uuids;
use App\Events\BidEvent; 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'ref_id', 
        'ref_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    private static function increment_user_count()
    {
        if( !$reg = Register::where('name', 'total_users')->first() )
            $reg = Register::create(['name'=>'total_users']); 
        $reg->value = (int)$reg->value+1; 
        $reg->save(); 
    }

    public function get_bidder($item_id)
    {
        $bidder = Bidder::where([ ['item_id', $item_id], ['user_id', $this->id] ])->first(); 
        if($bidder){
            return $bidder; 
        }
        return null; 
    }
    
    private static function get_new_ref()
    {
        $ref_id = session('ref_by'); 
        if ($ref_id){
            session()->forget('ref_by');
            return $ref_id; 
        }
    }
    /**
     * Search user by wallet address
     * @return User::class object
     * @param adddress string 
     */
    public static function findUserByAddress($address)
    {
        if( !$user = self::where('address', $address)->first() ){
            $user = self::create([
                'address'=>$address,
                'ref_id'=>generateRefCode(),
                'ref_by'=>self::get_new_ref()
            ]);
            self::increment_user_count(); 
        }
        return $user; 
    }

    public function creditBid($amt)
    {
        $this->total_credit += $amt; 
        $this->save(); 
    }

    public function creditRef($amt)
    {
        if($this->ref_by){
            if( $user = self::find($this->ref_by) ){
                $pct = (float)get_register('ref_pct'); 
                $amt = cal_pct($amt, $pct); 
                $user->creditBid($amt); 
            }
        }
    }
 
    public function placeBid(Item $item, $amt)
    {
        $bidder = Bidder::where([
            ['user_id', $this->id],
            ['item_id', $item->id]
        ])->first(); 
        if(!$bidder){
            $bidder = Bidder::create([
                'user_id'=>$this->id,
                'item_id'=>$item->id,
                'address'=>$this->address,
                'points'=>0
            ]); 
            $this->total_item += 1;
            $check_point = Point::where('network', $item->network)->first();
            $task = $this->get_uncompleted_task($item->network);
            if($check_point && $task){
                $task->total += 1; 
                if($task->total >= $task->rq_total){
                    $this->points += $task->reward;
                    $task->status = 1;  
                }
                $task->save(); 
            }
            $this->save();
        }

        $bidder->points += $amt;
        $item->points += $amt;
        $bidder->save();
        $item->save();
         
        //fire bid event here
        $bidders = Bidder::where('item_id', $item->id)->orderBy('updated_at', 'desc')->take(10)->get();
        $bidders = view('bidders', compact('bidders')); 
        $data = [
            'bidders'=>"$bidders",
            'type'=>'bidders'
        ];

        if($item->share > 1){
            $winners = $item->winners; 
            $points = $item->w_points; 

            if(!$winners){
                $item->winners = $this->id; 
                $item->w_points = $amt; 
            }else{
                $winners = explode(',', $winners);
                $points = explode(',', $points);

                if(count($winners) < $item->share){
                    $item->winners.= ','.$this->id;
                    $item->w_points.= ','.$amt;
                }else{
                    for($i = 0; $i < $item->share; $i++){
                        $point = (int)$points[$i]; 
                        if($bidder->points >= $point){
                            $points[$i] = $bidder->points; 
                            $winners[$i] = $this->id; 
                            break; 
                        }
                    }
                    $item->winners = implode(',', $winners);
                    $item->w_points = implode(',', $points);
                }
            }
            $item->save(); 
        }
        $this->creditBid($amt); 
        BidEvent::dispatch($data);
        return ['done'=>true]; 
    }

    public function get_name()
    {
        if($this->fname || $this->lname)
            $name = $this->fname." ".$this->lname;  
        else 
            $name = truncateAddress($this->address); 
        return $name; 
    }

    public function task_completed($point_id)
    {
        $check = TaskPoint::where([ 
            ['user_id', $this->id], 
            ['point_id', $point_id]
        ])->first();

        if($check && $check->status == 1)
            return true; 
        return false; 
    }

    public function get_completed_task($point_id)
    {
        $check = TaskPoint::where([ 
            ['user_id', $this->id], 
            ['point_id', $point_id]
        ])->first();
        if($check)
            return $check->total; 

        return 0; 
    }

    public function get_uncompleted_task($network)
    {
        $points = Point::where('network', $network)->get();  
        foreach($points as $point){
            $check = TaskPoint::where([ 
                ['user_id', $this->id], 
                ['point_id', $point->id]
            ])->first();
            if(!$check){
                TaskPoint::create([
                    'user_id'=>$this->id,
                    'point_id'=>$point->id,
                    'rq_total'=>$point->bid,
                    'reward'=>$point->reward,
                    'network'=>$point->network
                ]);
            }
        }
        return TaskPoint::where([ 
            ['user_id', $this->id], 
            ['network', $network],
            ['status', 0]
        ])->orderBy('reward', 'asc')->first();
    }


}
