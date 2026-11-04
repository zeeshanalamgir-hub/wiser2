<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobdisputebid extends Model
{
     use HasFactory;
    protected $table = 'job_dispute_bid';

    public function bidData() {
        return $this->hasMany(Jobdisputebiddata::class, 'dispute_id');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id','id');
    }

    // Define the relationship for the buyer
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id','id');
    }
    public function disputeNegotiations()
    {
        return $this->hasMany(Jobdisputebiddata::class, 'dispute_id', 'id');
    }
}
