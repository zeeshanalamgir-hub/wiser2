<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeResponse extends Model
{
    use HasFactory;
    protected $table = 'stripe_responses';
    protected $guarded = ['id'];

    protected $casts = [
        'response' => 'array',
    ];
}