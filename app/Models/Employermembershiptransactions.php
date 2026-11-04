<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employermembershiptransactions extends Model
{
    use HasFactory;
    protected $table = 'employer_membership_transactions';

    public function employerMembership()
    {
        return $this->belongsTo(Employermembership::class,'membership_id','id');
    }
}