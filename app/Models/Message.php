<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'ch_messages';

    protected $fillable = [
    'id',
    'from_id',
    'to_id',
    'job_id',   // ✅ add this
    'body',
    'attachment',
    'seen',
    'email_included',
    'phone_included',
];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
