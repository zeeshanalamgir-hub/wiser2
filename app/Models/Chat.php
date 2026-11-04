<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
       protected $fillable = [ 'message','ch_s_id','ch_r_id','status'];
       protected $primaryKey = 'ch_id'; 
       public function suser()
       {
           return $this->belongsTo(User::class,'ch_s_id');
       }

       public function ruser()
       {
           return $this->belongsTo(User::class,'ch_r_id');
       }

     
 
}
