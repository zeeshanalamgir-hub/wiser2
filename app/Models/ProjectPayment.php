<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    use HasFactory;
    protected $table = 'project_payments';

    protected $fillable = [
        'user_id',
        'job_id',
        'user_biding_id',
        'payment',
        'remaining_payment'
    ];

    // public function Skill()
    // {
    //     return $this->belongsTo(Skill::class);
    // }

}
