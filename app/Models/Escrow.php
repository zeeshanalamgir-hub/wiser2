<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    use HasFactory;
    protected $table = 'escrows';
    protected $fillable = ['user_id','status','code','type'];

    protected $casts = [
        'data' => 'array',
    ];
}
