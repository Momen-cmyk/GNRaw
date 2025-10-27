<?php

use App\Models\GeneralSetting;

/**
 * Site Information
 *  */
if (!function_exists('settings')) {
    function settings()
    {
        $settings = GeneralSetting::take(1)->first();
        if (!is_null($settings)) {
            return $settings;
        }

        // Return a default settings object if none exists
        return GeneralSetting::create([
            'site_title' => 'GN Raw',
            'site_email' => 'info@gnraw.com',
            'site_phone' => '',
        ]);
    }
}
