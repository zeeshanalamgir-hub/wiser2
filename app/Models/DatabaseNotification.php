<?php
namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseNotification;

class DatabaseNotification extends BaseNotification
{
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'sender_id',
        'click_url',
        'read_at',
        'created_at',
        'updated_at',
    ];
}
