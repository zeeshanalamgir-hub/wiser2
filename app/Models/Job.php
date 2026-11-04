<?php

namespace App\Models;

use App\Models\Jobworkdeliverybid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',
        'category_id',
        'sub_category_id',
        'amount',
        'delivery_date',
        'description',
        'requirements',
        'priority',
        'exp_type',
        'budgettype',
        'country',
        'weekly_hours',
    ];
    protected $casts = [
        'skill' => 'object'
    ];

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function booking()
    {
        return $this->hasOne(Booking::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function jobBiding()
    {
        return $this->hasMany(JobBiding::class, 'job_id');
    }
    public function userBiding()
    {
        return $this->hasMany(Userbiding::class, 'job_id');
    }

    public function commentCount()
    {
        return $this->hasMany(Comment::class, 'job_id');
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skills', 'job_id', 'skill_id');
    }
    public function jobSkills()
    {
        return $this->hasMany(Jobskill::class, 'job_id');
    }
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'Pending-Approval';
            case 1:
                return 'Active';
            case 2:
                return 'Close';
            case 3:
                return 'Completed';
            case 4:
                return 'Bidding Closed';
            default:
                return 'Unknown';  // Default case if status is not defined
        }
    }
    public function getJobStatusTextAttribute()
    {
        switch ($this->job_status) {
            case 1:
                return 'In Progress';

            default:
                return 'Unknown';  // Default case if status is not defined
        }
    }
    public function getJobTypeAttribute()
    {
        if ($this->budgettype == 'fixed') {
            return 'Fixed';
        } elseif ($this->budgettype == 'per_hour' || $this->budgettype == 'hourly') {
            return 'Hourly';
        } else {
            return 'N/A'; // Default value if budgettype is not recognized
        }
    }
    public function buyerCounterOfferData()
    {
        return $this->hasMany(Buyercounterofferdata::class, 'job_id', 'id');
    }
    public function buyerCounterOffer()
    {
        return $this->hasOne(Buyercounteroffer::class, 'job_id', 'id');
    }
    public function attachments()
    {
        return $this->hasMany(JobAttachements::class, 'job_id', 'id');
    }
    public function extraSkills()
    {
        return $this->hasMany(Newskillbyuser::class, 'term_id', 'id')->where('type',0); // Adjust this to your actual relationship
    }
    public function getExpTypeTextAttribute()
    {
        switch ($this->exp_type) {
            case 1:
                return 'Beginner';
            case 2:
                return 'Intermediate';
            case 3:
                return 'Experienced';
            default:
                return 'Unknown';  // Handle cases where the value is not 1, 2, or 3
        }
    }
    public function jobWorkDeliveryHourlyBids()
    {
        return $this->hasMany(Jobworkdeliveryhourlybid::class, 'job_id', 'id');
    }

    public function reviewRatings()
    {
        return $this->hasMany(ReviewRating::class, 'data_id', 'id');
    }
}