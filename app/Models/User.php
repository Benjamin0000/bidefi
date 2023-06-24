<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Uuids;

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

        $bidder->points += $amt; 
        $bidder->save();

        $item->points += $amt; 
        $item->save(); 

        if($bidder->points <= $item->free_bid)
            $amt = $item->free_bid - $bidder->points; 
         
        $this->bid_credit -= $amt; 
        $this->save(); 
        //fire bid event here
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
