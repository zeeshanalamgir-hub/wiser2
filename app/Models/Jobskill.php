<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobskill extends Model
{
    use HasFactory;
    protected $table = 'job_skills';

    public function Skill()
    {
        return $this->belongsTo(Skill::class);
    }
  
}
