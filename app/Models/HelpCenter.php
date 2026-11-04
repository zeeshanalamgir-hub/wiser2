<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpCenter extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title', 'slug', 'type'];

    public function details()
    {
        return $this->hasMany(HelpCenterDetail::class);
    }

    public function scopeType(Builder $builder, $type)
    {
        return $builder->where('type',$type);
    }
}
