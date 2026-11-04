<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'price',
        'delivery_time',
        'revision',
        'service_id',
        'user_id',
        'decline_reason'
    ];

    /**
     * Get the parent service that this detail belongs to.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the user who owns this service detail.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function tierValues()
    {
        return $this->hasMany(ServiceDetailTier::class, 'service_detail_id');
    }

    public function getTierItems()
    {
        return $this->tierValues()->with('tier')->get()->map(function ($item) {
            return [
                'label' => $item->tier->name ?? 'Feature',
                'type' => $item->tier->type ?? 'input',
                'value' => $item->service_value_tier,
            ];
        });
    }

}
