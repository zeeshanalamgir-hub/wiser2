<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidsLog extends Model
{
    use HasFactory;
    protected $table = 'bids_log';
    protected $guarded = [
        'id','user_id','bids','tr_op','post_balance','details', 'created_at', 'updated_at'
    ];
    
    
}