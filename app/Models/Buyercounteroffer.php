<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyercounteroffer extends Model
{
     use HasFactory;

    protected $table = 'buyer_counter_offers';
    protected $fillable = [
        'description',
        'file',
        'type',
        'bid_id',
        'job_id',
        'sender_id',
        'reciever_id',
        'allow_manual_time',
        'created_at','status',
        'updated_at',
        'request_description',
        'decline_reason',
        'close_job',
        'invite_professional',
        'is_manual_log_accepted',
        'rejected_reason',
    ];

    public function offerdata(){
        return $this->hasOne(Buyercounterofferdata::class, 'counter_offer_id');
    }
    public function buyerCounterOfferData()
    {
        return $this->hasMany(Buyercounterofferdata::class, 'counter_offer_id', 'id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }
    public function userBid()
    {
        return $this->belongsTo(Userbiding::class, 'bid_id', 'id');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'reciever_id');  // 'user_id' is the foreign key in buyercounteroffers table
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'sender_id');  // 'user_id' is the foreign key in buyercounteroffers table
    }
    public function getStatusLabel()
    {
        switch ($this->status) {
            case 0:
                return 'Offer Pending';
            case 1:
                return 'Offer Accepted';
            case 2:
                return 'Offer Rejected';
            default:
                return 'Unknown';
        }
    }
}
