<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSession extends Model
{
    protected $fillable = [
        'session_id',
        'buyer_id',
        'gateway_id',
        'amount',
        'payable_type',
        'payable_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
    
    use HasFactory;

}
