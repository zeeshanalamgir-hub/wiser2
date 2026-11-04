<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategories extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcat_id');
    }
    public function service()
    {
        return $this->hasMany(Service::class, 'childcat_id');
    }
}
