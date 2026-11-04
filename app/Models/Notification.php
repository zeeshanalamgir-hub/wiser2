<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table='notifications';
    protected $primaryKey = 'id';
    public $incrementing = false;  // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string since UUID is a string

    // Ensure UUID is generated before saving
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($notification) {
            if (!$notification->id) {
                $notification->id = (string) Str::uuid();  // Generate UUID
            }
        });
    }
    protected $fillable = [
        'type',
        'reciever_id', // Add this
        'sender_id',
        'job_id',
        'chat_id',
        'seen',
        'message',
        'click_url',
    ];

}
