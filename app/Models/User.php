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
        'address'
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

    /**
     * Search user by wallet address
     * @return User::class object
     * @param adddress string 
     */
    public static function findUserByAddress($address)
    {
        if( !$user = self::where('address', $address)->first() ){
            $user = self::create(['address'=>$address]);
            self::increment_user_count(); 
        }
        return $user; 
    }

    public function creditBid($amt)
    {
        $this->bid_credit += $amt; 
        $this->total_credit += $amt; 
        
        $this->save(); 
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
        }
        $free = false; 
        $left = 0; 
        $used = $bidder->points + $amt; 

        if($used <= $item->free_bid){
            $free = true; 
        }else{
            $free = false;
            if($item->free_bid > $bidder->points)
                $left = $item->free_bid - $bidder->points; 
        }

        if(!$free){
            $amt2 = $amt - $left;
            if($this->bid_credit < $amt2)
                return ['error'=>"Insufficient bid credit"]; 

            $this->bid_credit -= $amt2; 
            $this->save();
        }

        $bidder->points += $amt;
        $item->points += $amt;
        $bidder->save();
        $item->save();
         
        //fire bid event here
        $bidders = Bidder::where('item_id', $item->id)->orderBy('updated_at', 'desc')->take(10)->get();
        $data = [
            'bidders'=>"$bidders",
            'type'=>'bidders'
        ];
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


}
