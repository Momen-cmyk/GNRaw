<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
    dir="{{ config('app.available_locales')[app()->getLocale()]['rtl'] ? 'rtl' : 'ltr' }}">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('pageTitle')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Site favicon -->
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

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />
    <link rel="stylesheet" type="text/css" href="/extra-assets/ijabo/css/ijabo.min.css" />

    <!-- RTL Support for Arabic -->
    @if (config('app.available_locales')[app()->getLocale()]['rtl'] ?? false)
        <link rel="stylesheet" type="text/css" href="/css/rtl.css" />
    @endif

    <!-- Dark Mode Support -->
    <link rel="stylesheet" type="text/css" href="/css/dark-theme.css" />

    <!-- Bootstrap Icons for theme toggle -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    @kropifyStyles

    <!-- Flexible Logo Styles for Admin/Supplier Dashboard -->
    <style>
        /* Sidebar logo flexibility */
        .left-side-bar .brand-logo img.site_logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* keeps proportions */
            /* Make logo bigger */
            min-height: 80px;
            max-height: 120px;
        }

        /* Preloader logo */
        .pre-loader .loader-logo img {
            height: 100%;
            /* fill parent height */
            width: 100%;
            /* fill parent width */
            object-fit: contain;
        }

        /* Responsive adjustments for sidebar */
        @media (max-width: 1200px) {
            .left-side-bar .brand-logo img.site_logo {
                min-height: 70px;
                max-height: 100px;
            }
        }

        @media (max-width: 768px) {
            .left-side-bar .brand-logo img.site_logo {
                min-height: 60px;
                max-height: 90px;
            }

            .pre-loader .loader-logo img {
                min-height: 60px;
                max-height: 90px;
            }
        }

        @media (max-width: 480px) {
            .left-side-bar .brand-logo img.site_logo {
                min-height: 50px;
                max-height: 80px;
            }

            .pre-loader .loader-logo img {
                min-height: 50px;
                max-height: 80px;
            }
        }

        /* Ensure proper alignment */
        .left-side-bar .brand-logo {

            display: flex;
            align-items: center;
            justify-content: center;
            /* Increased padding for more space around the logo */
            padding: 25px 15px;
            /* Increased padding for bigger logo */

            /* ADDED: Set a minimum height for the logo area to ensure it's tall enough */
            min-height: 120px;

        }

        .left-side-bar .brand-logo a {
            display: inline-block;
            line-height: 0;
        }

        /* Logo hover effect */
        .left-side-bar .brand-logo img.site_logo:hover,
        .navbar-brand img:hover,
        .brand-logo img:hover {
            opacity: 0.8;
            transform: scale(1.02);
        }

        /* Enhanced sidebar styling */
        .left-side-bar {
            background: linear-gradient(135deg, #000000 0%, #777777 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }



        .left-side-bar .sidebar-menu ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(5px);
        }

        .left-side-bar .sidebar-menu ul li.active a {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .left-side-bar .sidebar-menu ul li .micon {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .left-side-bar .sidebar-menu ul li a:hover .micon {
            color: #ffffff;
            transform: scale(1.1);
        }

        .left-side-bar .sidebar-small-cap {
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 8px 10px;
        }

        .left-side-bar .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 15px 8px;
        }

        /* Brand logo styling */
        .left-side-bar .brand-logo {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .left-side-bar .brand-logo a {
            padding: 5px;
            /* Changed from 15px to 5px */
            display: inline-block;
            line-height: 0;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .left-side-bar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 280px;
            }

            .left-side-bar.show {
                transform: translateX(0);
            }

            .mobile-menu-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-menu-overlay.show {
                display: block;
            }

            .main-container {
                margin-left: 0 !important;
            }

            .user-info-dropdown .user-name {
                display: none;
            }

            .user-info-dropdown .dropdown-toggle {
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .left-side-bar {
                width: 100%;
            }

            .user-dropdown {
                min-width: 250px;
            }

            .user-info-header {
                padding: 15px;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
            }

            .user-details h4 {
                font-size: 14px;
            }
        }

        /* Smooth transitions for all sidebar elements */
        .left-side-bar * {
            transition: all 0.3s ease;
        }

        /* User Info Dropdown Styling */
        .user-info-dropdown {
            margin-left: 15px;
        }

        .user-info-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .user-info-dropdown .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #333;
            text-decoration: none;
        }

        .user-info-dropdown .user-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .user-info-dropdown .user-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info-dropdown .user-name {
            font-weight: 500;
            margin-right: 8px;
        }

        .user-info-dropdown .fa-chevron-down {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .user-info-dropdown .dropdown-toggle[aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }

        .user-dropdown {
            min-width: 280px;
            padding: 0;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .user-info-header {
            display: flex;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .user-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
        }

        .user-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .user-details h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .user-details p {
            margin: 0 0 5px 0;
            font-size: 12px;
            opacity: 0.8;
        }

        .user-role {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .user-dropdown .dropdown-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: #333;
            transition: all 0.3s ease;
        }

        .user-dropdown .dropdown-item:hover {
            background: #f8f9fa;
            color: #333;
        }

        .user-dropdown .dropdown-item i {
            margin-right: 10px;
            width: 16px;
            text-align: center;
        }

        .user-dropdown .logout-item {
            color: #dc3545;
        }

        .user-dropdown .logout-item:hover {
            background: #f8d7da;
            color: #721c24;
        }

        /* Notification Styles */
        .user-notification {
            margin-right: 15px;
        }

        .user-notification .dropdown-toggle {
            position: relative;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .user-notification .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #333;
            text-decoration: none;
        }

        .notification-active {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 3px solid #2196f3;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #333;
        }

        .notification-message {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }

        .notification-time {
            font-size: 11px;
            color: #999;
        }

        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 3px solid #2196f3;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f8f9fa;
        }

        .notification-title {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .notification-message {
            color: #666;
            font-size: 13px;
            line-height: 1.4;
        }

        .notification-actions {
            margin-top: 8px;
        }

        .notification-actions .btn {
            font-size: 11px;
            padding: 4px 8px;
        }

        .notification-item.unread .notification-title {
            font-weight: 700;
        }

        .notification-item.unread .notification-icon {
            background-color: #e3f2fd;
        }
    </style>

    @stack('stylesheets')

</head>

<body>
    {{--  Loading.. logo image --}}
    {{--
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : '' }}"
                    alt="" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div> --}}

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
        </div>
        <div class="header-right">
            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                        <i class="dw dw-settings2"></i>
                    </a>
                </div>
            </div>

            <!-- Admin Notifications -->
            <div class="user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown"
                        id="notificationDropdown">
                        <i class="icon-copy dw dw-notification"></i>
                        <span class="badge notification-active" id="notificationBadge" style="display: none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div
                            class="notification-header d-flex justify-content-between align-items-center p-3 border-bottom">
                            <h6 class="mb-0">Notifications</h6>
                            <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn"
                                style="display: none;">Mark All Read</button>
                        </div>
                        <div class="notification-list mx-h-350 customscroll" id="notificationList">
                            <div class="text-center text-muted py-3">
                                <i class="fa fa-bell-slash fa-2x mb-2"></i>
                                <p>No notifications</p>
                            </div>
                        </div>
                        <div class="notification-footer p-2 border-top text-center">
                            <a href="{{ route('admin.notifications') }}" class="btn btn-sm btn-outline-primary">View
                                All</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info Dropdown -->
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img src="{{ Auth::user()->picture }}" alt="{{ Auth::user()->name }}"
                                onerror="this.src='/images/users/admin_default.png'" />
                        </span>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="fa fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dropdown">
                        <div class="user-info-header">
                            <div class="user-avatar">
                                <img src="{{ Auth::user()->picture }}" alt="{{ Auth::user()->name }}"
                                    onerror="this.src='/images/users/admin_default.png'" />
                            </div>
                            <div class="user-details">
                                <h4>{{ Auth::user()->name }}</h4>
                                <p>{{ Auth::user()->email }}</p>
                                <span
                                    class="user-role">{{ Auth::guard('admin')->check() ? 'Admin' : (Auth::guard('supplier')->check() ? 'Supplier' : 'User') }}</span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ Auth::guard('supplier')->check() ? route('admin.profile') : route('admin.profile') }}"
                            class="dropdown-item">
                            <i class="fa fa-user"></i> Profile
                        </a>
                        <a href="{{ Auth::guard('supplier')->check() ? route('admin.settings') : route('admin.settings') }}"
                            class="dropdown-item">
                            <i class="fa fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ Auth::guard('supplier')->check() ? route('admin.logout') : route('admin.logout') }}"
                            class="dropdown-item logout-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Logout Form -->
            <form id="logout-form"
                action="{{ Auth::guard('supplier')->check() ? route('admin.logout') : route('admin.logout') }}"
                method="POST" style="display: none;">
                @csrf
            </form>

        </div>
    </div>

    <div class="right-sidebar">
        <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
                Layout Settings
                <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
        <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
                <h4 class="weight-600 font-18 pb-10">Header Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
                <div class="sidebar-radio-group pb-10 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-1" checked="" />
                        <label class="custom-control-label" for="sidebaricon-1"><i
                                class="fa fa-angle-down"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-2" />
                        <label class="custom-control-label" for="sidebaricon-2"><i
                                class="ion-plus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-3" />
                        <label class="custom-control-label" for="sidebaricon-3"><i
                                class="fa fa-angle-double-right"></i></label>
                    </div>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
                <div class="sidebar-radio-group pb-30 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-1" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-1" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-1"><i
                                class="ion-minus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-2" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-2" />
                        <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o"
                                aria-hidden="true"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-3" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-3" />
                        <label class="custom-control-label" for="sidebariconlist-3"><i
                                class="dw dw-check"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-4" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-4" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-4"><i
                                class="icon-copy dw dw-next-2"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-5" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-5" />
                        <label class="custom-control-label" for="sidebariconlist-5"><i
                                class="dw dw-fast-forward-1"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-6" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-6" />
                        <label class="custom-control-label" for="sidebariconlist-6"><i
                                class="dw dw-next"></i></label>
                    </div>
                </div>

                <div class="reset-options pt-30 text-center">
                    <button class="btn btn-danger" id="reset-settings">
                        Reset Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="/">
                <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.svg' }}"
                    alt="Logo" class="dark-logo site_logo" />
                <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.svg' }}"
                    alt="Logo" class="light-logo site_logo" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    @if (Auth::guard('admin')->check())
                        <!-- Admin Menu -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-home"></span><span class="mtext">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-cube"></span><span class="mtext">Products</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('admin.products') }}">All Products</a></li>
                                <li><a href="{{ route('admin.products.drafts') }}">Draft Products</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('admin.customers') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-users"></span><span class="mtext">Customers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.suppliers.index') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-industry"></span><span class="mtext">Suppliers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-th-list"></span><span class="mtext">Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.certificates') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-certificate"></span><span class="mtext">Certificates</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.supplier-certificates') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-users"></span><span class="mtext">Supplier
                                    Certificates</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-bar-chart"></span><span class="mtext">Reports</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <div class="sidebar-small-cap">Blog Management</div>
                        </li>
                        <li>
                            <a href="{{ route('admin.blog-categories.index') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-folder"></span>
                                <span class="mtext">Blog Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.blog-posts.index') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-newspaper-o"></span>
                                <span class="mtext">Blog Posts</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <div class="sidebar-small-cap">Settings</div>
                        </li>
                        <li>
                            <a href="{{ route('admin.profile') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-user-circle"></span>
                                <span class="mtext">Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-cogs"></span>
                                <span class="mtext">Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.logout') }}" class="dropdown-toggle no-arrow"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="micon fa fa-sign-out"></span>
                                <span class="mtext">Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @else
                        <!-- Supplier Menu -->
                        <li>
                            <a href="{{ route('supplier.dashboard') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-home"></span><span class="mtext">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-th-list"></span><span class="mtext">Categories</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon fa fa-newspaper-o"></span><span class="mtext"> Posts </span>
                            </a>
                            <ul class="submenu">
                                <li><a href="">New</a></li>
                                <li><a href="">Post</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon fa fa-shopping-bag"></span><span class="mtext">Shop</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="">New Product</a></li>
                                <li><a href="">All Product</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="invoice.html" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-receipt-cutoff"></span><span class="mtext">Invoice</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <div class="sidebar-small-cap">Settings</div>
                        </li>
                        <li>
                            <a href="{{ route('supplier.profile') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-user-circle"></span>
                                <span class="mtext">Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('supplier.settings') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-cogs"></span>
                                <span class="mtext">Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('supplier.logout') }}" class="dropdown-toggle no-arrow"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="micon fa fa-sign-out"></span>
                                <span class="mtext">Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('supplier.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="">
                    @yield('content')
                </div>
            </div>
            <div class="footer-wrap pd-20 mb-20 card-box">Made by Eng. <a
                    href="https://www.linkedin.com/in/momen-tarek/" target="_blank">Momen Tarek </a>
            </div>
        </div>
    </div>

    <!-- js -->
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    <script src="/extra-assets/ijabo/js/ijabo.min.js?v=2"></script>
    @kropifyScripts

    <script>
        window.addEventListener('showToastr', function(event) {
            $().notifa({
                vers: 1,
                cssClass: event.detail[0].type,
                html: event.detail[0].message,
                delay: 3000,
            })

        });

        // Enhanced sidebar functionality
        function initSidebar() {
            // Add active class to current page menu item
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('#accordion-menu a');

            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.parentElement.classList.add('active');
                }
            });
        }

        // Mobile sidebar toggle functionality
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.left-side-bar');
            const overlay = document.querySelector('.mobile-menu-overlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Close sidebar when clicking overlay
        function closeMobileSidebar() {
            const sidebar = document.querySelector('.left-side-bar');
            const overlay = document.querySelector('.mobile-menu-overlay');

            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        // Close sidebar when clicking outside on mobile
        function handleOutsideClick(event) {
            const sidebar = document.querySelector('.left-side-bar');
            const menuIcon = document.querySelector('.menu-icon');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                !menuIcon.contains(event.target)) {
                closeMobileSidebar();
            }
        }

        // Initialize sidebar on page load
        document.addEventListener('DOMContentLoaded', function() {
            initSidebar();

            // Add mobile menu toggle functionality
            const menuIcon = document.querySelector('.menu-icon');
            const overlay = document.querySelector('.mobile-menu-overlay');

            if (menuIcon) {
                menuIcon.addEventListener('click', toggleMobileSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeMobileSidebar);
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileSidebar();
                }
            });

            // Handle outside clicks
            document.addEventListener('click', handleOutsideClick);
        });

        // Admin Notifications
        function loadNotifications() {
            fetch('/admin/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        function loadNotificationList() {
            fetch('/admin/notifications/recent', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notificationList');
                    const markAllBtn = document.getElementById('markAllReadBtn');

                    if (data.notifications && data.notifications.length > 0) {
                        markAllBtn.style.display = 'block';

                        let notificationsHtml = '';
                        data.notifications.forEach(notification => {
                            const actionIcon = notification.data?.action_icon || 'fa-bell';
                            const actionColor = notification.data?.action_color || 'primary';
                            const productId = notification.data?.product_id;
                            const productName = notification.data?.product_name || 'Unknown Product';
                            const supplierName = notification.data?.supplier_name || 'Unknown Supplier';

                            notificationsHtml += `
                            <div class="notification-item ${!notification.is_read ? 'unread' : ''}" data-id="${notification.id}">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon me-3">
                                        <i class="fa ${actionIcon} text-${actionColor}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="notification-content">
                                            <h6 class="notification-title mb-1">${notification.title}</h6>
                                            <p class="notification-message mb-2">${notification.message}</p>
                                            <div class="notification-actions">
                                                ${productId ? `<a href="/admin/products/${productId}" class="btn btn-sm btn-outline-primary me-2" target="_blank"><i class="fa fa-eye"></i> View Product</a>` : ''}
                                                ${!notification.is_read ? `<button class="btn btn-sm btn-outline-success mark-read-btn" data-id="${notification.id}"><i class="fa fa-check"></i> Mark Read</button>` : '<span class="text-muted small"><i class="fa fa-check-circle"></i> Read</span>'}
                                            </div>
                                        </div>
                                        <small class="notification-time text-muted">
                                            <i class="fa fa-clock"></i> ${new Date(notification.created_at).toLocaleString()}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                        list.innerHTML = notificationsHtml;

                        // Add event listeners for mark as read buttons
                        document.querySelectorAll('.mark-read-btn').forEach(btn => {
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                const notificationId = this.getAttribute('data-id');
                                markNotificationAsRead(notificationId);
                            });
                        });
                    } else {
                        markAllBtn.style.display = 'none';
                        list.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <i class="fa fa-bell-slash fa-2x mb-2"></i>
                            <p>No notifications</p>
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error loading notification list:', error);
                    // Fallback to simple count
                    loadNotificationListFallback();
                });
        }

        function loadNotificationListFallback() {
            fetch('/admin/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notificationList');
                    const markAllBtn = document.getElementById('markAllReadBtn');

                    if (data.count > 0) {
                        markAllBtn.style.display = 'block';
                        list.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <i class="fa fa-bell fa-2x mb-2"></i>
                            <p>You have ${data.count} unread notifications</p>
                            <a href="/admin/notifications" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    `;
                    } else {
                        markAllBtn.style.display = 'none';
                        list.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <i class="fa fa-bell-slash fa-2x mb-2"></i>
                            <p>No notifications</p>
                        </div>
                    `;
                    }
                })
                .catch(error => console.error('Error loading notification count:', error));
        }

        function markNotificationAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the specific notification in the UI
                        const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                        if (notificationItem) {
                            notificationItem.classList.remove('unread');
                            const markReadBtn = notificationItem.querySelector('.mark-read-btn');
                            if (markReadBtn) {
                                markReadBtn.outerHTML =
                                    '<span class="text-muted small"><i class="fa fa-check-circle"></i> Read</span>';
                            }
                        }

                        // Update the badge count
                        loadNotifications();
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
        }

        function markAllNotificationsAsRead() {
            fetch('/admin/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                        loadNotificationList();
                    }
                })
                .catch(error => console.error('Error marking all notifications as read:', error));
        }

        // Initialize notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            loadNotificationList();

            // Set up mark all read button
            const markAllReadBtn = document.getElementById('markAllReadBtn');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    markAllNotificationsAsRead();
                });
            }
        });

        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
    </script>
    @stack('scripts')
</body>

</html>
