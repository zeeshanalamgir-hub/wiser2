<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesAddson extends Model
{
    use HasFactory;

    protected $table = 'services_addson';

    protected $fillable = ['name', 'cat_id', 'sub_cat_id'];

    public function userAddsOn()
    {
        return $this->hasMany(UserServicesAddsOn::class, 'services_addson_id');
    }
}

