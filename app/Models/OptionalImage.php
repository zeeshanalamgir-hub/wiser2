<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionalImage extends Model
{
    use HasFactory;
    protected $table = 'optional_images';
    protected $fillable = 
    [
        'service_id',
        'software_id',
        'image'
    ];
}
