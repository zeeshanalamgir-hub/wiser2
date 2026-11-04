<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttachements extends Model
{
    use HasFactory;
    protected $table = 'job_attachements';

    protected $fillable = [ 'job_id','user_id','image_url'];


}
