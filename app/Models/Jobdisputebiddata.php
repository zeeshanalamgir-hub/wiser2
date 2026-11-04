<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobdisputebiddata extends Model
{
    use HasFactory;
    protected $table = 'job_dispute_bid_data';

    // Define buyer relationship
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Define seller relationship
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
