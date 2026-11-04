<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfileAlerts extends Model
{
   use HasFactory;
   protected $table = 'user_profile_alerts';

    /**
     * Relationship with EmailTemplate.
     */
    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_sms_template_id');
    }
}
