<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $guarded = ['id'];

    protected $table = 'email_sms_templates';

    protected $casts = [
        'shortcodes' => 'object'
    ];

    public function userProfileAlerts()
    {
        return $this->hasMany(UserProfileAlerts::class, 'email_sms_template_id', 'id');
    }
}
