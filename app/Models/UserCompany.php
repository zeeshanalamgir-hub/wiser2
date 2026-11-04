<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserCompany extends Model
{
    use HasFactory;
    protected $table = "user_company";

    // Add all the fields that can be mass-assigned
    protected $fillable = [
        'user_id',       // Foreign key for the user
        'companyname',   // Name of the company
        'companyweb',    // Website of the company
        'companyind',    // Industry of the company
        'director',      // Director or Proprietor
        'contact',       // Contact number
        'timezone',      // Time zone
        'country',       // Country
        'state',         // State
        'address',       // Address
        'zip',           // Zip code
        'description',   // Company description
        'created_at',    // Timestamp
        'updated_at',    // Timestamp
    ];

    public function setCompanyWebAttribute($value)
    {
        $this->attributes['companyweb'] = "https://".$value;
    }

    public function getCompanyWebAttribute($value)
    {
        $value = Str::replaceFirst('https://', '', $value);
        $value = Str::replaceFirst('http://', '', $value);
        return rtrim($value, '/');
    }

    public function getContactAttribute($value)
    {
        $value = Str::replaceFirst('+', '', $value);
        return rtrim($value, '/');
    }
}
