<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicedetailskillnewextra extends Model
{
    use HasFactory;
    protected $table = 'seller_detail_skill_new_extra';
    protected $fillable = [
        'service_id',
        'user_id',
        'name',
    ];
}
