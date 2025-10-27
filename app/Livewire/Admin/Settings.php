<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\GeneralSetting;

class Settings extends Component
{
    public $tab = null;
    public $default_tab = 'general_settings';
    protected $queryString = ['tab' => ['Keep' => true]];

    //General Setting form propertes
    public $site_title, $site_email, $site_phone, $site_address, $site_city, $site_state, $site_country, $site_zip_code, $site_website, $facebook_url, $twitter_url, $instagram_url, $linkedin_url, $youtube_url, $whatsapp_number, $telegram_username, $site_description, $site_meta_keywords, $site_meta_description;

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = Request('tab') ? Request('tab') : $this->default_tab;
        $settings = GeneralSetting::take(1)->first();
        if (!is_null($settings)) {
            $this->site_title = $settings->site_title;
            $this->site_email = $settings->site_email;
            $this->site_phone = $settings->site_phone;
            $this->site_address = $settings->site_address;
            $this->site_city = $settings->site_city;
            $this->site_state = $settings->site_state;
            $this->site_country = $settings->site_country;
            $this->site_zip_code = $settings->site_zip_code;
            $this->site_website = $settings->site_website;
            $this->facebook_url = $settings->facebook_url;
            $this->twitter_url = $settings->twitter_url;
            $this->instagram_url = $settings->instagram_url;
            $this->linkedin_url = $settings->linkedin_url;
            $this->youtube_url = $settings->youtube_url;
            $this->whatsapp_number = $settings->whatsapp_number;
            $this->telegram_username = $settings->telegram_username;
            $this->site_description = $settings->site_description;
            $this->site_meta_keywords = $settings->site_meta_keywords;
            $this->site_meta_description = $settings->site_meta_description;
        }
    }
    public function updateSiteInfo()
    {
        $this->validate([
            'site_title' => 'required',
            'site_email' => 'required|email',

        ]);
        $settings = GeneralSetting::take(1)->first();
        $data = array(
            'site_title' => $this->site_title,
            'site_email' => $this->site_email,
            'site_phone' => $this->site_phone,
            'site_address' => $this->site_address,
            'site_city' => $this->site_city,
            'site_state' => $this->site_state,
            'site_country' => $this->site_country,
            'site_zip_code' => $this->site_zip_code,
            'site_website' => $this->site_website,
            'facebook_url' => $this->facebook_url,
            'twitter_url' => $this->twitter_url,
            'instagram_url' => $this->instagram_url,
            'linkedin_url' => $this->linkedin_url,
            'youtube_url' => $this->youtube_url,
            'whatsapp_number' => $this->whatsapp_number,
            'telegram_username' => $this->telegram_username,
            'site_description' => $this->site_description,
            'site_meta_keywords' => $this->site_meta_keywords,
            'site_meta_description' => $this->site_meta_description,
        );
        if (!is_null($settings)) {
            $query = $settings->update($data);
        } else {
            $query = GeneralSetting::insert($data);
        }
        if ($query) {
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'General settings  have been updated successfully. '
            ]);
        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Something went wrong. '
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
