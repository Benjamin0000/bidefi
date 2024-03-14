<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class ChatMsg extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'user_id',
        'msg'
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class); 
    }
}
