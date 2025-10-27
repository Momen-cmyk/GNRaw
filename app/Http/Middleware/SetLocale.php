<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is in session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // Check if locale is in request
        elseif ($request->has('locale')) {
            $locale = $request->get('locale');
            Session::put('locale', $locale);
        }
        // Check browser language
        elseif ($request->server('HTTP_ACCEPT_LANGUAGE')) {
            $locale = $this->detectBrowserLanguage($request);
        }
        // Default to app locale
        else {
            $locale = config('app.locale');
        }

        // Validate locale is supported
        $availableLocales = array_keys(config('app.available_locales', ['en']));
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.fallback_locale');
        }

        // Set application locale
        App::setLocale($locale);

        // Set locale in session for persistence
        Session::put('locale', $locale);

        return $next($request);
    }

    /**
     * Detect browser language preference
     */
    private function detectBrowserLanguage(Request $request)
    {
        $acceptLanguage = $request->server('HTTP_ACCEPT_LANGUAGE');

        if (!$acceptLanguage) {
            return config('app.fallback_locale');
        }

        // Parse Accept-Language header
        $languages = explode(',', $acceptLanguage);
        $preferredLanguage = explode(';', $languages[0])[0];

        // Map browser languages to our supported locales
        $languageMap = [
            'zh' => 'zh-CN',
            'zh-CN' => 'zh-CN',
            'ar' => 'ar',
            'hi' => 'hi',
            'de' => 'de',
            'en' => 'en',
        ];

        foreach ($languageMap as $browserLang => $appLocale) {
            if (strpos($preferredLanguage, $browserLang) === 0) {
                return $appLocale;
            }
        }

        return config('app.fallback_locale');
    }
}
