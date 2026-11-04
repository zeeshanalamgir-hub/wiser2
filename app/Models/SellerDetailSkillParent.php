<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerDetailSkillParent extends Model
{
    use HasFactory;
    protected $table = 'seller_detail_skill_parent';
    protected $fillable = ['service_id','user_id','parent','child'];
}
