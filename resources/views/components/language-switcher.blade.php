<div class="language-switcher dropdown">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown"
        data-bs-toggle="dropdown" aria-expanded="false">
        @php
            $currentLocale = app()->getLocale();
            $currentLanguage = config('app.available_locales')[$currentLocale] ?? config('app.available_locales')['en'];
        @endphp
        <span class="flag">{{ $currentLanguage['flag'] }}</span>
        <span class="lang-name d-none d-md-inline ms-1">{{ $currentLanguage['name'] }}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
        @foreach (config('app.available_locales') as $locale => $language)
            <li>
                <a class="dropdown-item {{ app()->getLocale() == $locale ? 'active' : '' }}"
                    href="{{ route('language.switch', $locale) }}">
                    <span class="flag me-2">{{ $language['flag'] }}</span>
                    <span>{{ $language['name'] }}</span>
                    @if (app()->getLocale() == $locale)
                        <i class="bi bi-check-lg ms-2 text-primary"></i>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>

<style>
    .language-switcher .dropdown-menu {
        min-width: 180px;
    }

    .language-switcher .flag {
        font-size: 1.2em;
    }

    .language-switcher .dropdown-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
    }

    .language-switcher .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .language-switcher .dropdown-item.active {
        background-color: #e7f1ff;
        color: #0d6efd;
    }
</style>
