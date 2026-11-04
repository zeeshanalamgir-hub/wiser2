<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends DatabaseNotification
{
    use HasFactory;
    protected $table = 'admin_notifications';
    protected $primaryKey = 'id'; 
    public $incrementing = true;
    
    protected $fillable = [
        'user_id',
        'title',
        'click_url',
    ];
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}