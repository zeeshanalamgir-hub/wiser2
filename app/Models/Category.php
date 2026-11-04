<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    public function subCategory()
    {
    	return $this->hasMany(SubCategory::class, 'category_id');
    }
    public function childCategory()
    {
        return $this->hasMany(ChildCategories::class, 'cat_id');
    }
    public function service()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
    public function skills()
    {
        return $this->hasMany(Skill::class, 'category_id');
    }
    public function jobs()
    {
        return $this->hasMany(Job::class, 'category_id');
    }
}