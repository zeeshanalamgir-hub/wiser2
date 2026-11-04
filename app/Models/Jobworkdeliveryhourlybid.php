<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobworkdeliveryhourlybid extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id', 'buyer_id', 'bid_id', 'job_id', 'status', 'data_id',
        'date_start', 'date_end', 'time', 'minutes', 'total_cost', 'per_hour_rate',
        'title', 'start_time', 'end_time', 'timezone' , 'disputed_hours','disputed_minutes','disputed_amount','timezone','memo','elapsed_time_in_seconds'// Add new fields here
    ];
    protected $table = 'job_work_delivery_houly_bid';

    public function intervals()
    {
        return $this->hasMany(JobworkIntervalEntry::class, 'hourly_bid_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'job_work_delivery_hourly_bid_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class);  // The `job_id` field should exist in the `job_work_delivery_hourly_bid` table
    }
    public function buyerCounterOffer()
    {
        return $this->belongsTo(BuyerCounterOffer::class, 'data_id', 'id'); // Adjust if your foreign key differs
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');  // 'user_id' is the foreign key in buyercounteroffers table
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');  // 'user_id' is the foreign key in buyercounteroffers table
    }
}
