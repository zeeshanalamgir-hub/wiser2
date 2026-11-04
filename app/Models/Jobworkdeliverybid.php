<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobworkdeliverybid extends Model
{
    use HasFactory;
    protected $table = 'job_work_delivery_bid';

    public function getStatusLabel()
    {
        switch ($this->status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Accepted';
            case 2:
                return 'Delivered';
            case 3:
                return 'Rejected';
            case 4:
                return 'In Progress';
            case 5:
                return 'Paid';
            case 6:
                return 'Dispute';
            default:
                return 'Unknown';
        }
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function counterOfferData()
    {
        return $this->belongsTo(Buyercounterofferdata::class, 'data_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    public function dispute()
    {
        return $this->belongsTo(Jobdisputebid::class,'work_id','id');
    }
}
