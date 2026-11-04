<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServicesAddsOn extends Model
{
    use HasFactory;

    protected $table = 'user_services_adds_on';

    protected $fillable = [
        'user_id',
        'services_addson_id',
        'price',
        'delivery_time',
        'service_id',
        'service_detail_id',
    ];

    public function addOn()
    {
        return $this->belongsTo(ServicesAddson::class, 'services_addson_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function serviceDetail()
    {
        return $this->belongsTo(ServicesDetail::class, 'service_detail_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
