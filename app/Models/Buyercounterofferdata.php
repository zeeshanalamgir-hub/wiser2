<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyercounterofferdata extends Model
{
    use HasFactory;
    protected $table = 'buyer_counter_offers_data';
    protected $fillable = [
        'title',
        'amount',
        'days',
        'revisions',
        'counter_offer_id',
        'bid_id',
        'sender_id',
        'reciever_id',
        'job_id',
        'status',
        'milestone_status',
        'req_type',
        'req_responce_status',
        'is_payable',
        'disputed_amount',
        'due_date',
        'created_at',
        'updated_at',
    ];


    public function buyerCounterOffer()
    {
        return $this->belongsTo(Buyercounteroffer::class, 'counter_offer_id', 'id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }

    public function jobWorkDeliveryBid()
    {
        return $this->hasMany(Jobworkdeliverybid::class, 'data_id', 'id'); // Make sure to adjust the foreign key and local key as necessary
    }
    public function jobDisputeBid()
    {
        return $this->hasOne(Jobdisputebid::class, 'offer_id', 'id'); // Make sure to adjust the foreign key and local key as necessary
    }
    /**
     * Get the status label based on the status.
     *
     * @return string
     */
    public function getStatusLabel()
    {
        switch ($this->status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Accepted';
            case 2:
                return 'Rejected';
            default:
                return 'Unknown';
        }
    }
    /**
     * Get the textual representation of the milestone status.
     *
     * @return string
     */
    public function getMilestoneStatusLabel()
    {
        switch ($this->milestone_status) {
            case 0:
                return 'Not Requested';
            case 1:
                return 'Revision Requested';
            case 2:
                return 'Requested';
            case 3:
                return 'Paid';
            case 6:
                return 'Disputed';
            case 7:
                return 'Refund';
            case 8:
                return 'Milestone Requested';
            case 9:
                return 'Milestone Request Rejected';
            default:
                return 'Unknown Status';
        }
    }
}
