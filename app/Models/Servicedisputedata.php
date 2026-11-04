<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicedisputedata extends Model
{
    use HasFactory;
    protected $table = 'service_dispute_data';

    protected $fillable = [
        'booking_id',
        'service_id',
        'seller_id',
        'buyer_id',
        'order_number',
        'reason',
        'disputed_amount',
        'buyer_amount',
        'seller_amount',
        'message',
        'status',
        'send_by',
        'file' // include this too if you're saving uploaded file names
    ];

    // ✅ Buyer relationship
    public function buyer()
    {
        return $this->belongsTo(\App\Models\User::class, 'buyer_id');
    }

    // ✅ Seller relationship
    public function seller()
    {
        return $this->belongsTo(\App\Models\User::class, 'seller_id');
    }

    public function disputeNegotiations()
    {
        return $this->hasMany(\App\Models\Servicedisputeoffers::class, 'dispute_id', 'id');
    }

    public function booking()
    {
        return $this->belongsTo(\App\Models\Booking::class, 'booking_id');
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'service_id');
    }

}