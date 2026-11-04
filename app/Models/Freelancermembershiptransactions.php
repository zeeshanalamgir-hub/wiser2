<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelancermembershiptransactions extends Model
{
    use HasFactory;
    protected $table = 'freelancer_membership_transactions';

    protected $guarded = ['id'];

    protected $casts = [
        'last_rewarded_at' => 'datetime',
    ];

    public function membership()
    {
        return $this->belongsTo(Freelancermembership::class, 'membership_id', 'id');
    }
}
