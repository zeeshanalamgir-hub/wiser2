<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMilestone extends Model
{
     use HasFactory;
    protected $table = 'user_milestone';

    protected $fillable =[
        'bid_id',
        'title',
        'due_date',
        'amount'
    ];

    protected $guarded = ['id'];

    public function bid()
    {
        return $this->belongsTo(UserBiding::class, 'bid_id');
    }

}
