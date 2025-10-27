<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', settings()->site_title ?? '')</title>

    <!-- Favicon -->
    @if (isset(settings()->site_favicon) && settings()->site_favicon)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/site/' . settings()->site_favicon) }}">
        <link rel="icon" type="image/png" sizes="16x16"
            href="{{ asset('images/site/' . settings()->site_favicon) }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/site/' . settings()->site_favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    @endif

    <!-- Bootstrap CSS -->
    <link href="{{ asset('back/src/plugins/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('back/src/fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    @stack('styles')

    <!-- Frame 114 Header Styles -->
    <style>
        /* Frame 114 Header */
        .frame-114-header {
            position: relative;
            width: 100vw;
            height: 140px;
            margin: 0;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        /* Language Selector */
        .language-selector {
            position: absolute;
            left: 2%;
            top: 51px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            background: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .language-selector:hover {
            background: #e9ecef;
            transform: scale(1.05);
        }

        .language-text {
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 19px;
            color: #000000;
        }

        /* Language Arrow */
        .language-arrow {
            width: 10px;
            height: 5px;
            transition: transform 0.3s ease;
        }

        .arrow-down {
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #000000;
        }

        /* Language Dropdown */
        .language-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: #FFFFFF;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            min-width: 120px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .language-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .language-option {
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #000000;
        }

        .language-option:hover {
            background-color: #f8f9fa;
        }

        .language-option:first-child {
            border-radius: 5px 5px 0 0;
        }

        .language-option:last-child {
            border-radius: 0 0 5px 5px;
        }


        /* Site Logo */
        .site-logo {
            position: absolute;
            left: 50%;
            top: 35%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .site-logo:hover {
            transform: translate(-50%, -50%) scale(1.05);
        }

        .site-logo a {
            display: block;
            text-decoration: none;
        }

        .site-logo img {
            max-height: 100px;
            width: 250px;
            height: auto;
        }

        .logo-text {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-sidebar {
            display: none;
        }

        /* Navigation Menu */
        .main-menu {
            position: absolute;
            width: auto;
            max-width: 500px;
            height: 19px;
            left: 50%;
            top: 93px;
            transform: translateX(-50%);
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            padding: 0px;
            gap: 30px;
            justify-content: center;
        }

        .menu-link {
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 19px;
            color: #000000;
            text-decoration: none;
            flex: none;
            flex-grow: 0;
        }

        .menu-link:hover {
            color: #007bff;
        }

        /* Menu Dropdown */
        .menu-dropdown {
            position: relative;
            display: inline-block;
        }

        .menu-dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
            background: #FFFFFF;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .menu-dropdown-content.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 10px 15px;
            color: #000000;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #f1f1f1;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-toggle {
            position: relative;
        }

        .dropdown-toggle::after {
            content: '';
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #000000;
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .menu-dropdown:hover .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        /* Menu Arrow */
        .menu-arrow {
            position: absolute;
            width: 10px;
            height: 5px;
            left: 50%;
            top: 100px;
            transform: translateX(-50%);
        }

        /* Auth Buttons */
        .auth-buttons {
            position: absolute;
            right: 2%;
            top: 16px;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-login {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 5px 26px;
            gap: 10px;
            width: 96px;
            height: 29px;
            background: #FFFFFF;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 19px;
            text-align: center;
            color: #000000;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #f8f9fa;
        }

        .btn-register {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 5px 26px;
            gap: 10px;
            width: 107px;
            height: 29px;
            background: linear-gradient(90deg, #5961ff 0%, #0003bc 100%);
            border: none;
            border-radius: 5px;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 19px;
            text-align: center;
            color: #FFFFFF;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(255, 162, 81, 0.3);
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 15px;
            color: #000000;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
        }

        .user-link:hover {
            color: #007bff;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: absolute;
            right: 1.5%;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            flex-direction: column;
            gap: 4px;
            padding: 8px;
            z-index: 1001;
        }

        .hamburger-line {
            width: 24px;
            height: 2px;
            background: #000000;
            border-radius: 1px;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .mobile-menu-toggle:hover .hamburger-line {
            background: #007bff;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            background: #FFFFFF;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .mobile-menu.show {
            left: 0;
        }

        /* Mobile Menu Backdrop */
        .mobile-menu-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu-content {
            padding: 60px 20px 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .mobile-menu-link {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #000000;
            text-decoration: none;
            padding: 12px 0;
            border-bottom: 1px solid #f1f1f1;
            transition: all 0.3s ease;
        }

        .mobile-menu-link:hover {
            color: #007bff;
            padding-left: 10px;
        }

        .mobile-menu-link:last-child {
            border-bottom: none;
        }

        /* Mobile Menu Dropdown */
        .mobile-menu-dropdown {
            position: relative;
        }

        .mobile-menu-dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #f8f9fa;
            margin-left: 20px;
        }

        .mobile-menu-dropdown-content.show {
            max-height: 300px;
        }

        .mobile-dropdown-item {
            display: block;
            padding: 8px 20px;
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .mobile-dropdown-item:hover {
            color: #007bff;
            background: #ffffff;
        }

        .mobile-dropdown-item:last-child {
            border-bottom: none;
        }

        .mobile-menu-dropdown .dropdown-toggle::after {
            content: '';
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #000000;
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .mobile-menu-dropdown.active .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        /* Responsive Design */
        @media (max-width: 1440px) {
            .frame-114-header {
                padding: 0 2%;
            }
        }

        @media (max-width: 1200px) {
            .main-menu {
                gap: 20px;
            }

            .auth-buttons {
                right: 1.5%;
            }

            .site-logo img {
                max-height: 80px;
                width: auto;
            }
        }

        @media (max-width: 992px) {
            .main-menu {
                display: none;
            }

            .menu-arrow {
                display: none;
            }

            .language-selector {
                left: 1.5%;
            }

            .mobile-menu-toggle {
                display: flex !important;
                right: 1.5%;
                top: 50%;
                transform: translateY(-50%);
            }

            .mobile-menu {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .frame-114-header {
                height: auto;
                padding: 15px;
                flex-direction: column;
                gap: 15px;
            }

            .auth-buttons {
                position: static;
                order: 1;
                align-self: flex-end;
                margin-bottom: 10px;
            }

            .language-selector {
                position: static;
                order: 2;
                align-self: flex-start;
            }

            .site-logo {
                position: static;
                order: 3;
                transform: none;
                align-self: center;
            }

            .site-logo img {
                max-height: 60px;
                width: auto;
            }

            .main-menu {
                display: none;
            }

            .menu-arrow {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .frame-114-header {
                padding: 10px;
                gap: 10px;
            }

            .main-menu {
                display: none;
            }

            .site-logo img {
                max-height: 50px;
                width: auto;
            }

            .auth-buttons {
                flex-direction: column;
                gap: 8px;
                align-self: stretch;
                order: 1;
            }

            .btn-login,
            .btn-register {
                width: 100%;
                text-align: center;
                padding: 8px 16px;
                font-size: 14px;
            }

            .language-selector {
                align-self: flex-start;
                order: 2;
            }

            .site-logo {
                order: 3;
            }
        }

        /* Additional fixes for better compatibility */
        .frame-114-header * {
            box-sizing: border-box;
        }

        .menu-link:focus,
        .btn-login:focus,
        .btn-register:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
        }

        /* Ensure proper stacking */
        .frame-114-header {
            position: sticky;
            top: 0;
        }
    </style>
</head>

<body>
    <!-- Frame 114 Header -->
    <header class="frame-114-header">
        <!-- Language Selector -->
        {{-- <div class="language-selector">
            <span class="language-text" id="currentLanguage">En</span>
            <div class="language-arrow">
                <div class="arrow-down"></div>
            </div>
            <div class="language-dropdown" id="languageDropdown">
                <div class="language-option" data-lang="en">English</div>
                <div class="language-option" data-lang="ar">العربية</div>
                <div class="language-option" data-lang="fr">Français</div>
                <div class="language-option" data-lang="es">Español</div>
                <div class="language-option" data-lang="de">Deutsch</div>
            </div>
        </div> --}}

        <!-- Site Logo -->
        <div class="site-logo">

            <span class="logo-text"><a href="/">
                    <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.svg' }}"
                        alt="Logo" height="auto" width="auto" />
                </a>
                <div class="close-sidebar" data-toggle="left-sidebar-close">
                    <i class="ion-close-round"></i>
                </div>
            </span>
        </div>

        <!-- Navigation Menu -->
        <nav class="main-menu">
            <a href="{{ route('home') }}" class="menu-link">Home</a>
            <a href="{{ route('products') }}" class="menu-link">Products</a>
            @if (isset($categories) && $categories->count() > 0)
                <div class="menu-dropdown">
                    <a href="{{ route('categories') }}" class="menu-link dropdown-toggle">Categories</a>
                    <div class="menu-dropdown-content" id="categoriesDropdown">
                        <a href="{{ route('categories') }}" class="dropdown-item">All Categories</a>
                        @foreach ($categories->take(5) as $category)
                            <a href="{{ route('category.products', $category->slug) }}"
                                class="dropdown-item">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
            <a href="{{ route('blog.index') }}" class="menu-link">Blog</a>
            <a href="{{ route('about') }}" class="menu-link">About</a>
            <a href="{{ route('contact') }}" class="menu-link">Contact</a>
        </nav>

        <!-- Menu Arrow -->


        <!-- Mobile Menu Button -->
        <button class="mobile-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Auth Buttons -->
        <div class="auth-buttons">
            @auth
                <!-- Show when user is logged in -->
                <div class="user-dropdown">
                    <a href="#" class="user-link" data-bs-toggle="dropdown">
                        <i class="fa fa-user"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i
                                    class="fa fa-tachometer-alt"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fa fa-user"></i>
                                Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fa fa-sign-out-alt"></i>
                                    Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <!-- Show when user is not logged in -->
                <a href="{{ route('login') }}" class="btn-login">LogIn</a>
                <a href="{{ route('register') }}" class="btn-register">SignUp</a>
            @endauth
        </div>

        <!-- Mobile Menu Backdrop -->
        <div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-content">
                <a href="{{ route('home') }}" class="mobile-menu-link">Home</a>
                <a href="{{ route('products') }}" class="mobile-menu-link">Products</a>
                @if (isset($categories) && $categories->count() > 0)
                    <div class="mobile-menu-dropdown">
                        <a href="{{ route('categories') }}" class="mobile-menu-link dropdown-toggle">Categories</a>
                        <div class="mobile-menu-dropdown-content">
                            <a href="{{ route('categories') }}" class="mobile-dropdown-item">All Categories</a>
                            @foreach ($categories->take(5) as $category)
                                <a href="{{ route('category.products', $category->slug) }}"
                                    class="mobile-dropdown-item">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <a href="{{ route('blog.index') }}" class="mobile-menu-link">Blog</a>
                <a href="{{ route('about') }}" class="mobile-menu-link">About</a>
                <a href="{{ route('contact') }}" class="mobile-menu-link">Contact</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-brand mb-4">
                        @if (settings()->site_logo)
                            <div class="logo-container">
                                <img src="{{ asset('images/site/' . settings()->site_logo) }}"
                                    alt="{{ settings()->site_title ?? '' }}" class="footer-logo">
                                <div class="logo-glow"></div>
                            </div>
                        @else
                            <div class="text-logo-container">
                                <h4 class="fw-bold mb-0 text-white">
                                    <i class="fa fa-pills me-2 text-primary"></i>{{ settings()->site_title ?? '' }}
                                </h4>
                                <div class="logo-underline"></div>
                            </div>
                        @endif
                    </div>
                    <p class="text-light mb-3">
                        {{ settings()->site_description ?? 'Your trusted partner for premium pharmaceutical and nutraceutical products. We connect you with verified suppliers and quality products.' }}
                    </p>
                    <!-- Social Links -->
                    <div class="social-links">
                        @if (settings()->facebook_url)
                            <a href="{{ settings()->facebook_url }}" target="_blank" title="Facebook">
                                <i class="fab fa-facebook"></i>
                            </a>
                        @endif
                        @if (settings()->twitter_url)
                            <a href="{{ settings()->twitter_url }}" target="_blank" title="X">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                        @endif
                        @if (settings()->instagram_url)
                            <a href="{{ settings()->instagram_url }}" target="_blank" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if (settings()->linkedin_url)
                            <a href="{{ settings()->linkedin_url }}" target="_blank" title="LinkedIn">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        @endif
                        @if (settings()->youtube_url)
                            <a href="{{ settings()->youtube_url }}" target="_blank" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                        @if (settings()->whatsapp_number)
                            <a href="https://wa.me/{{ str_replace(['+', ' ', '-', '(', ')'], '', settings()->whatsapp_number) }}"
                                target="_blank" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                        @if (settings()->telegram_username)
                            <a href="https://t.me/{{ str_replace('@', '', settings()->telegram_username) }}"
                                target="_blank" title="Telegram">
                                <i class="fab fa-telegram-plane"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}"
                                class="text-light text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="{{ route('products') }}"
                                class="text-light text-decoration-none">Products</a></li>
                        <li class="mb-2"><a href="{{ route('categories') }}"
                                class="text-light text-decoration-none">Categories</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}"
                                class="text-light text-decoration-none">About</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}"
                                class="text-light text-decoration-none">Contact</a></li>
                    </ul>
                </div>

                <!-- Support Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Support</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('contact') }}"
                                class="text-light text-decoration-none">Contact Us</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Privacy
                                Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Terms of
                                Service</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Help Center</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Contact Information</h6>
                    @if (settings()->site_email)
                        <div class="contact-item mb-3">
                            <i class="fa fa-envelope me-2 text-primary"></i>
                            <a href="mailto:{{ settings()->site_email }}" class="text-light text-decoration-none">
                                {{ settings()->site_email }}
                            </a>
                        </div>
                    @endif
                    @if (settings()->site_phone)
                        <div class="contact-item mb-3">
                            <i class="fa fa-phone me-2 text-primary"></i>
                            <a href="tel:{{ settings()->site_phone }}" class="text-light text-decoration-none">
                                {{ settings()->site_phone }}
                            </a>
                        </div>
                    @endif
                    @if (settings()->site_address)
                        <div class="contact-item mb-3">
                            <i class="fa fa-map-marker me-2 text-primary"></i>
                            <span class="text-light">
                                {{ settings()->site_address }}
                                @if (settings()->site_city)
                                    <br>{{ settings()->site_city }}
                                @endif
                                @if (settings()->site_state)
                                    , {{ settings()->site_state }}
                                @endif
                                @if (settings()->site_country)
                                    <br>{{ settings()->site_country }}
                                @endif
                                @if (settings()->site_zip_code)
                                    {{ settings()->site_zip_code }}
                                @endif
                            </span>
                        </div>
                    @endif
                    @if (settings()->site_website)
                        <div class="contact-item mb-3">
                            <i class="fa fa-globe me-2 text-primary"></i>
                            <a href="{{ settings()->site_website }}" target="_blank"
                                class="text-light text-decoration-none">
                                Visit Our Website
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Bottom -->
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">
                        &copy; {{ date('Y') }} {{ settings()->site_title ?? '' }}. All rights
                        reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-light mb-0">
                        Made with <i class="fa fa-heart text-danger"></i> Eng. Momen Tarek
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <!-- jQuery -->
    <script src="{{ asset('back/src/scripts/jquery.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('back/src/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <!-- Popper JS -->
    <script src="{{ asset('back/src/plugins/bootstrap/popper.min.js') }}"></script>

    @stack('scripts')

    <!-- Mobile Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            const mobileMenu = document.querySelector('.mobile-menu');
            const mobileBackdrop = document.querySelector('.mobile-menu-backdrop');

            function toggleMenu() {
                mobileMenu.classList.toggle('show');
                mobileBackdrop.classList.toggle('show');

                // Toggle hamburger animation
                const lines = mobileToggle.querySelectorAll('.hamburger-line');
                lines.forEach((line, index) => {
                    if (mobileMenu.classList.contains('show')) {
                        if (index === 0) line.style.transform = 'rotate(45deg) translate(5px, 5px)';
                        if (index === 1) line.style.opacity = '0';
                        if (index === 2) line.style.transform = 'rotate(-45deg) translate(7px, -6px)';
                    } else {
                        line.style.transform = 'none';
                        line.style.opacity = '1';
                    }
                });
            }

            if (mobileToggle && mobileMenu && mobileBackdrop) {
                // Toggle button click
                mobileToggle.addEventListener('click', toggleMenu);

                // Backdrop click to close
                mobileBackdrop.addEventListener('click', toggleMenu);

                // Close menu when clicking on links
                const menuLinks = mobileMenu.querySelectorAll('.mobile-menu-link');
                menuLinks.forEach(link => {
                    link.addEventListener('click', toggleMenu);
                });
            }

            // Mobile Categories Dropdown Functionality
            const mobileCategoriesDropdown = document.querySelector('.mobile-menu-dropdown');
            const mobileCategoriesDropdownContent = document.querySelector('.mobile-menu-dropdown-content');

            if (mobileCategoriesDropdown && mobileCategoriesDropdownContent) {
                const dropdownToggle = mobileCategoriesDropdown.querySelector('.dropdown-toggle');

                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    mobileCategoriesDropdown.classList.toggle('active');
                    mobileCategoriesDropdownContent.classList.toggle('show');
                });
            }

            // Language Dropdown Functionality
            const languageSelector = document.querySelector('.language-selector');
            const languageDropdown = document.querySelector('.language-dropdown');
            const currentLanguage = document.querySelector('#currentLanguage');
            const languageArrow = document.querySelector('.language-arrow');

            if (languageSelector && languageDropdown && currentLanguage) {
                languageSelector.addEventListener('click', function(e) {
                    e.stopPropagation();
                    languageDropdown.classList.toggle('show');
                    languageArrow.style.transform = languageDropdown.classList.contains('show') ?
                        'rotate(180deg)' : 'rotate(0deg)';
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!languageSelector.contains(e.target)) {
                        languageDropdown.classList.remove('show');
                        languageArrow.style.transform = 'rotate(0deg)';
                    }
                });

                // Handle language selection
                const languageOptions = languageDropdown.querySelectorAll('.language-option');
                languageOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const lang = this.getAttribute('data-lang');
                        const langText = this.textContent;

                        // Update current language display
                        currentLanguage.textContent = lang.toUpperCase();

                        // Close dropdown
                        languageDropdown.classList.remove('show');
                        languageArrow.style.transform = 'rotate(0deg)';

                        // Here you can add language switching logic
                        console.log('Language changed to:', lang);

                        // You can redirect to language-specific URL or use AJAX to change language
                        // window.location.href = '/language/' + lang;
                    });
                });
            }

            // Categories Dropdown Functionality
            const categoriesDropdown = document.querySelector('.menu-dropdown');
            const categoriesDropdownContent = document.querySelector('#categoriesDropdown');

            if (categoriesDropdown && categoriesDropdownContent) {
                categoriesDropdown.addEventListener('mouseenter', function() {
                    categoriesDropdownContent.classList.add('show');
                });

                categoriesDropdown.addEventListener('mouseleave', function() {
                    categoriesDropdownContent.classList.remove('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!categoriesDropdown.contains(e.target)) {
                        categoriesDropdownContent.classList.remove('show');
                    }
                });
            }
        });
    </script>

    <style>
        /* Custom styles for user dropdown */
        .navbar-nav .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 8px 0;
            margin-top: 8px;
        }

        .navbar-nav .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
            color: #333;
            transition: all 0.2s ease;
        }

        .navbar-nav .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .navbar-nav .dropdown-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .navbar-nav .dropdown-divider {
            margin: 4px 0;
        }

        .navbar-nav .dropdown-toggle::after {
            margin-left: 8px;
        }

        /* Footer Styling */
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .footer-brand img {
            max-height: 100px;
            width: auto;
            filter: brightness(1);
            opacity: 1;
        }

        .footer-brand h5 {
            color: #fff;
            font-size: 1.5rem;
        }

        /* Footer Social Links Styling */
        .social-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .social-links a:hover {
            background-color: #007bff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            text-decoration: none;
            color: #fff;
        }

        .social-links a i {
            font-size: 16px;
        }

        /* Footer Links */
        footer ul li a {
            transition: all 0.3s ease;
            padding: 2px 0;
        }

        footer ul li a:hover {
            color: #007bff !important;
            padding-left: 5px;
        }

        /* Contact Items */
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .contact-item i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Footer Bottom */
        footer hr {
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Responsive Footer */
        @media (max-width: 768px) {
            .social-links {
                justify-content: center;
                margin-top: 20px;
            }

            footer .text-md-end {
                text-align: center !important;
                margin-top: 20px;
            }
        }

        /* Logo Styles */
        .navbar-brand img {
            max-height: 50px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        .navbar-brand span {
            font-size: 1.5rem;
        }

        /* Footer Logo Styles */
        .footer-logo {
            max-height: 80px;
            width: auto;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .logo-container {
            position: relative;
            display: inline-block;
            padding: 10px;
        }

        .logo-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
            border-radius: 10px;
            filter: blur(8px);
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .logo-container:hover .logo-glow {
            opacity: 1;
        }

        .logo-container:hover .footer-logo {
            transform: scale(1.1);
            filter: brightness(0) invert(1) drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .text-logo-container {
            position: relative;
            display: inline-block;
        }

        .logo-underline {
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .text-logo-container:hover .logo-underline {
            transform: scaleX(1);
        }

        .text-logo-container:hover h4 {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }
    </style>
</body>

</html>
