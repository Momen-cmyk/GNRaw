<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'site_title',
        'site_email',
        'site_phone',
        'site_address',
        'site_city',
        'site_state',
        'site_country',
        'site_zip_code',
        'site_website',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'whatsapp_number',
        'telegram_username',
        'site_description',
        'site_logo',
        'site_meta_keywords',
        'site_meta_description',
        'site_favicon'
    ];
}
