<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceptedbid extends Model
{
    use HasFactory;
    protected $table = 'accepted_bid';

    protected $fillable = [ 'job_id','bid_id','buyer_id','seller_id', 'is_endcontract', 'status'];

    public function bidStatus(){
        return $this->hasOne(AcceptedBidStatus::class, 'status_value', 'status');
    }
    public function getStatusTextAttribute()
    {
        // Use the 'status' field to determine its meaning
        switch ($this->attributes['status']) {
            case 0:
                return 'Active';
            case 1:
                return 'Completed';
            case 2:
                return 'Cancelled';
            case 2:
                return 'Completed';
            // Add more cases if there are additional status codes
            default:
                return 'Unknown';
        }
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');  // 'user_id' is the foreign key in buyercounteroffers table
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');  // 'user_id' is the foreign key in buyercounteroffers table
    }
    public function userBiding()
    {
        return $this->belongsTo(Userbiding::class, 'bid_id', 'id'); // 'bid_id' in accepted_bid links to 'id' in user_biding
    }
}
