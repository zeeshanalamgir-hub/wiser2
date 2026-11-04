<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContactUs extends Model
{
    use HasFactory;

    protected $table = 'user_contact_us';

    protected $fillable = [
        'fullname',
        'email',
        'company',
        'subject',
        'message',
    ];
}
