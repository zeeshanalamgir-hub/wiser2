<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    protected $table = 'services';
    protected $fillable = 
    [
        'featured',
        'user_id',
        'category_id',
        'sub_category_id',
        'chilcat_id',
        'title',
        'image',
        'price',
        'delivery_time',
        'tag',
        'description',
        'favorite',
        'rating',
        'likes',
        'dislike',
        'status',
        'customization',
        'priority',
        'cat_name',
        'subcat_name',
        'childcat_name'
    ];

    protected $casts = [
        'tag' => 'object'
    ];

    public function featuresService()
    {
        return $this->belongsToMany(Features::class, 'features_services', 'service_id', 'features_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
    public function childCategory()
    {
        return $this->belongsTo(ChildCategories::class, 'childcat_id');
    }

    public function extraService()
    {
        return $this->hasMany(ExtraService::class, 'service_id');
    }

    public function optionalImage()
    {
        return $this->hasMany(OptionalImage::class, 'service_id');
    }
    public function tiers()
    {
        return $this->hasMany(ServiceDetailTier::class, 'service_id');
    }
    // public function basictiers()
    // {
    //     return $this->hasMany(ServiceDetailTiersBasic::class, 'service_id');
    // }
    // public function standardtiers()
    // {
    //     return $this->hasMany(ServiceDetailTiersStandard::class, 'service_id');
    // }
    // public function singletiers()
    // {
    //     return $this->hasMany(ServiceDetailTiersSingle::class, 'service_id');
    // }
    // public function premiumtiers()
    // {
    //     return $this->hasMany(ServiceDetailTiersPremium::class, 'service_id');
    // }

    public function reviewCount()
    {
        return $this->hasMany(ReviewRating::class, 'service_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'services_skill', 'service_id', 'skill_id');
    }

    public function extraSkills()
    {
        return $this->hasMany(SellerDetailSkillNewExtra::class, 'service_id');
    }
    
    public function serviceDetail()
    {
        return $this->hasOne(ServicesDetail::class, 'service_id'); // Assuming foreign key is 'service_id'
    }

    public function userAddsOn()
    {
        return $this->hasMany(UserServicesAddsOn::class, 'service_id');
    }
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'Pending-Approval';
            case 1:
                return 'Active';
            case 2:
                return 'Closed';
            case 3:
                return 'Completed';
            case 4:
                return 'Bidding Closed';
            default:
                return 'Unknown';  // Default case if status is not defined
        }
    }

    public function detailSkillParent()
    {
        return $this->hasMany(Servicedetailskillparent::class);
    }

    public function latestDetail()
    {
        return $this->hasOne(ServicesDetail::class)->oldestOfMany();
    }

    // In Service model
    public function details()
    {
        return $this->hasMany(ServicesDetail::class, 'service_id');
    }

    public function reviews()
    {
        return $this->hasMany(ReviewRating::class, 'service_id');
    }

    public function languages()
    {
        return $this->hasMany(UserLanguage::class, 'user_id');
    }


    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id');
    }

    // public function earnings()
    // {
    //     return $this->hasMany(Earning::class, 'user_id');
    // }

}
