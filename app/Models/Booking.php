<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    const UPDATED_AT = null;

    protected $casts = [
        'extra_service' => 'object',
        'user_extra_services'=>'json'
    ];
    protected $fillable = [
        'buyer_id',
        'service_id',
        'service_detail_id',
        'user_extra_services',
        'job_id',
        'software_id',
        'seller_id',
        'qty',
        'amount',
        'agreed_amount',
        'otheramount',
        'service_price',
        'discount',
        'order_number',
        'extra_service',
        'status',
        'working_status',
        'dispute_report',
        'service_days',
        'service_revisions',
        'service_package',
        'is_endcontract',
        'otherprice',
        'othersymbol',
        'currentrate',
        'seller_platform_fee',
        'buyer_service_fee',
        'booking_type',
        'admin_reason',
        'status_updated_at',
        'is_extension_requested',
        'is_extension_approved',
        'extra_revisions'
    ];

    public function extensionRequest()
    {
        return $this->hasOne(ServiceExtension::class, 'booking_id');
    }

    public function service()
    {
    	return $this->belongsTo(Service::class,'service_id');
    }

    public function serviceDetail()
    {
    	return $this->belongsTo(ServicesDetail::class,'service_id');
    }

     public function serviceDetails()
    {
    	return $this->belongsTo(ServicesDetail::class,'service_detail_id');
    }
    

    public function software()
    {
    	return $this->belongsTo(Software::class,'software_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id','id');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function workFile()
    {
        return $this->hasMany(WorkDelivery::class, 'booking_id');
    }
    public function buyerCounterOffers()
    {
        return $this->hasOne(Buyercounteroffer::class, 'id','service_id');
    }
    public function getWorkingStatusTextAttribute()
    {
        switch ($this->working_status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Completed';
            case 2:
                return 'Delivered';
            case 3:
                return 'Cancel';
            case 4:
                return 'In Progress';
            case 5:
                return 'Expired';
            case 6:
                return 'Dispute';
            case 7:
                return 'Payed';
            default:
                return 'Unknown';  // Default case if status is not defined
        }
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'booking_id', 'id');
    }

    public function activeTransactions()
    {
        return $this->hasMany(Transaction::class, 'booking_id', 'id')->where('status',1);
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class, 'booking_id', 'id')->latest();
    }

    public function getCanDisputeAttribute()
    {
        return Carbon::parse($this->updated_at)
            ->addDays(7)
            ->greaterThan(now());
    }
}
