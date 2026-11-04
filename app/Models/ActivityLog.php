<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';
    protected $fillable = [
        'job_work_intervals_id',
        'job_work_delivery_hourly_bid_id',
        'keyboard_activity',
        'mouse_activity',
        'log_time',
    ];
    public $timestamps = true;

}
