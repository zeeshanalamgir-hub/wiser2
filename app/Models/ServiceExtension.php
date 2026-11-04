<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'booking_id',
        'buyer_id',
        'seller_id',
        'from_extension_date',
        'upto_extension_date',
        'is_approved',
        'change_status_date',
        'details',
    ];
}
