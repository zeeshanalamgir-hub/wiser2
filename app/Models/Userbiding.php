<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userbiding extends Model
{
    use HasFactory;
    protected $table = 'user_biding';
    protected $guarded = [
        'id'
    ];

    public function biddingStatus()
    {
        return $this->belongsTo(UserBiddingStatus::class, 'status', 'status_value');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    public function projectPayments()
    {
        return $this->hasMany(ProjectPayment::class, 'user_bidding_id', 'id');
    }
    public function onlyjob()
    {
        return $this->hasOne(Job::class, 'job_id');
    }
    public function JobworkFile()
    {
        return $this->hasMany(JobWorkDelivery::class, 'userbiding_id');
    }
    public function buyerCounterOffer()
    {
        return $this->hasOne(Buyercounteroffer::class, 'bid_id', 'id');
    }
    public function buyerCounterOfferMany()
    {
        return $this->hasMany(Buyercounteroffer::class, 'bid_id', 'id')->orderBy('created_at', 'desc');
    }
    public function userMilestones()
    {
        return $this->hasMany(UserMilestone::class, 'bid_id');
    }
    public function skills()
    {
        return $this->hasMany(Jobskill::class);
    }
    public function getSellerOfferResponceTextAttribute()
    {
        switch ($this->attributes['seller_offer_responce']) {
            case 0:
                return 'Bid Placed';
            case 1:
                return 'Offer Rejected';
            case 2:
                return 'Offer Accepted';
            case 3:
                return 'Offer Received';
            case 4:
                return 'Bid Closed';
            case 5:
                return 'Offer Cancelled';
            default:
                return 'Unknown'; // In case there's any unexpected value
        }
    }
    public function milestones()
    {
        return $this->hasMany(UserMilestone::class, 'bid_id');
    }
    /**
     * Fetch user data based on bid ID, seller_offer_response, and specific columns.
     *
     * @param int $bidId
     * @param array $columns
     * @return Userbiding|null
     */
    public static function getUserByBidId($bidId, array $columns = ['id', 'firstname', 'lastname', 'email'])
    {
        // Ensure that 'id' is always included in the selected columns for proper relationship fetching
        if (!in_array('id', $columns)) {
            array_unshift($columns, 'id');
        }

        // Fetch Userbiding with the specific bidId and seller_offer_response = 2
        return self::where('id', $bidId)
            ->where('seller_offer_response', 2)
            ->with(['user:' . implode(',', $columns)]) // Use dynamic columns
            ->first();
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id'); // User who received the rating
    }
    public function attachments()
    {
        return $this->hasMany(JobAttachements::class, 'job_id', 'job_id')
            ->where('user_id', $this->user_id); // Filter by the user_id automatically
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
