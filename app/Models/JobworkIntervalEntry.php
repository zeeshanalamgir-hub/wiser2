<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobworkIntervalEntry extends Model
{
    use HasFactory;
    protected $table = 'job_work_intervals';
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    protected $fillable = [
        'hourly_bid_id',
        'interval_start_time',
        'interval_end_time',
        'status', // 1 for active, 0 for deleted
        'screenshot_path','is_manual'
    ];

    public function hourlyBid()
    {
        return $this->belongsTo(Jobworkdeliveryhourlybid::class, 'hourly_bid_id', 'id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'job_work_intervals_id', 'id');
    }
}
