<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'point_id',
        'rq_total',
        'total',
        'network',
        'reward'
    ]; 
}
