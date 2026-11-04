<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRating extends Model
{
    use HasFactory;

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
    	return $this->belongsTo(Service::class, 'service_id');
    }

    public function software()
    {
    	return $this->belongsTo(Software::class, 'software_id');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id'); // User who gave the rating
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'pro_id'); // User who received the rating
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'data_id'); // User who received the rating
    }
}
