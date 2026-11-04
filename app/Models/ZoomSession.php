<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'host_user_id',
        'session_name',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }
}
