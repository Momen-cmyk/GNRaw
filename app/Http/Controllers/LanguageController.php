<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch application language
     */
    public function switch(Request $request, $locale)
    {
        // Validate locale
        $availableLocales = array_keys(config('app.available_locales', ['en']));

        if (!in_array($locale, $availableLocales)) {
            return redirect()->back()->with('error', 'Language not supported');
        }

        // Set locale in session
        Session::put('locale', $locale);
        App::setLocale($locale);

        return redirect()->back()->with('success', 'Language changed successfully');
    }
}
