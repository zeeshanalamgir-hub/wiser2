<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpCenterDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['help_center_id', 'sub_title', 'desc'];

    public function helpCenter()
    {
        return $this->belongsTo(HelpCenter::class);
    }
}
