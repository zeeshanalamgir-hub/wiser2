<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicedisputeoffers extends Model
{
    use HasFactory;
    protected $table = 'service_dispute_offer';

    protected $fillable = [
        'dispute_id',
        'disputed_amount',
        'buyer_amount',
        'seller_amount',
        'message',
        'status',
        'send_by'
    ];

}