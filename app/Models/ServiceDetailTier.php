<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDetailTier extends Model
{
    use HasFactory;
    protected $fillable = ['service_value_tier', 'service_tier_id', 'service_detail_id'];


    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function tier()
    {
        return $this->belongsTo(ServiceTiers::class, 'service_tier_id');
    }
}
