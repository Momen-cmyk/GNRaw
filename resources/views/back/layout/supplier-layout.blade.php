<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('pageTitle')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site favicon -->
    @if (isset(settings()->site_favicon) && settings()->site_favicon)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/site/' . settings()->site_favicon) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/site/' . settings()->site_favicon) }}">
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
    @kropifyStyles


    <!-- Flexible Logo Styles for Admin/Supplier Dashboard -->
    <style>
        /* Sidebar logo fix */
        .left-side-bar .brand-logo img.site_logo {
            max-height: 45px;
            /* Keep consistent height */
            width: auto;
            /* Keep aspect ratio */
            object-fit: contain;
            /* Prevent distortion */
            image-rendering: -webkit-optimize-contrast;
            /* Improve clarity in browsers */
            display: block;
            /* Prevent extra spacing */
            margin: 0 auto;
            /* Center the logo */
        }

        /* Preloader logo fix */
        .pre-loader .loader-logo img {
            max-height: 70px;
            /* Smaller preloader */
            width: auto;
            object-fit: contain;
            image-rendering: -webkit-optimize-contrast;
        }

        /* Sidebar logo flexibility */
        .left-side-bar .brand-logo img.site_logo {
            min-height: 80px;
            max-height: 120px;
            max-width: 200px;
            height: auto;
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        /* Preloader logo */
        .pre-loader .loader-logo img {
            min-height: 80px;
            max-height: 120px;
            max-width: 200px;
            height: auto;
            width: auto;
            object-fit: contain;
        }

        /* Responsive adjustments for sidebar */
        @media (max-width: 1200px) {
            .left-side-bar .brand-logo img.site_logo {
                min-height: 70px;
                max-height: 100px;
                max-width: 180px;
            }
        }

        @media (max-width: 768px) {
            .left-side-bar .brand-logo img.site_logo {
                min-height: 60px;
                max-height: 90px;
                max-width: 160px;
            }

            .pre-loader .loader-logo img {
                min-height: 60px;
                max-height: 90px;
                max-width: 160px;
            }
        }

        @media (max-width: 480px) {
            .left-side-bar .brand-logo img.site_logo {
                min-height: 50px;
                max-height: 80px;
                max-width: 140px;
            }

            .pre-loader .loader-logo img {
                min-height: 50px;
                max-height: 80px;
                max-width: 140px;
            }
        }

        /* Ensure proper alignment */
        .left-side-bar .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px 15px;
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
            /* Changed from 15px to 5px to match admin style */
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

        /* Notification Dropdown Styles */
        .notification-dropdown {
            margin-right: 15px;
        }

        .notification-toggle {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .notification-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #333;
            text-decoration: none;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .notification-dropdown-menu {
            min-width: 350px;
            max-width: 400px;
            max-height: 500px;
            overflow-y: auto;
            padding: 0;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .notification-item.urgent {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
        }

        .notification-item.urgent.unread {
            background-color: #ffcdd2;
        }

        .notification-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .notification-message {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .notification-time {
            color: #999;
            font-size: 12px;
        }

        .notification-actions {
            margin-top: 10px;
        }

        .notification-actions .btn {
            font-size: 12px;
            padding: 5px 10px;
        }
    </style>
    @stack('stylesheets')
</head>

<body>
    {{-- <!-- Preloader -->
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.png' }}"
                    alt="GNRAW Logo" class="supplier-logo" />
            </div>
            <div class="loader-progress" id="loader-progress">
                <div class="bar" data-percentage="0" style="width: 0%;"></div>
            </div>
            <div class="loader-dot"></div>
        </div>
    </div> --}}

    {{-- <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" onclick="toggleSidebar()" id="sidebarToggle">
        <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.png' }}"
            alt="GNRAW" class="sidebar-toggle-logo"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="sidebar-toggle-text" style="display: none;">
            <span class="toggle-brand">GN</span>
            <span class="toggle-nutra">RAW</span>
        </div>
    </button> --}}

    <!-- Header -->
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
                            <a href="{{ route('supplier.notifications') }}" class="btn btn-sm btn-outline-primary">View
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
                                onerror="this.src='/images/users/default-avatar.png'" />
                        </span>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="fa fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dropdown">
                        <div class="user-info-header">
                            <div class="user-avatar">
                                <img src="{{ Auth::user()->picture }}" alt="{{ Auth::user()->name }}"
                                    onerror="this.src='/images/users/default-avatar.png'" />
                            </div>
                            <div class="user-details">
                                <h4>{{ Auth::user()->name }}</h4>
                                <p>{{ Auth::user()->email }}</p>
                                <span class="user-role">Supplier</span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('supplier.profile') }}" class="dropdown-item">
                            <i class="fa fa-user"></i> Profile
                        </a>
                        <a href="{{ route('supplier.settings') }}" class="dropdown-item">
                            <i class="fa fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('supplier.logout') }}" class="dropdown-item logout-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('supplier.logout') }}" method="POST" style="display: none;">
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
            <a href="{{ route('supplier.dashboard') }}">
                <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.svg' }}"
                    alt="Logo" class="dark-logo site_logo" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <!-- Supplier Menu -->
                    <li>
                        <a href="{{ route('supplier.dashboard') }}" class="dropdown-toggle no-arrow">

                            <span class="micon fa fa-home"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supplier.products') }}" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-cube"></span><span class="mtext">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supplier.reports') }}" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-bar-chart"></span><span class="mtext">Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supplier.notifications') }}" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-bell"></span><span class="mtext">Notifications</span>
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
                    href="https://www.linkedin.com/in/momen-tarek/" target="_blank">Momen Tarek</a>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    <script src="/extra-assets/ijabo/js/ijabo.min.js"></script>
    @kropifyScripts

    <!-- Flash Messages -->
    <script>
        window.addEventListener('load', function() {
            // Hide preloader
            document.querySelector('.pre-loader').style.display = 'none';
        });

        // Flash message handling
        document.addEventListener('DOMContentLoaded', function() {
            // Handle flash messages
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if (session('fail'))
                toastr.error('{{ session('fail') }}');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}');
                @endforeach
            @endif
        });

        // Enhanced sidebar functionality - matching pages-layout
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

        // Theme management
        function initTheme() {
            const savedTheme = localStorage.getItem('supplier-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('supplier-theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const themeIcon = document.getElementById('theme-icon');
            if (themeIcon) {
                if (theme === 'dark') {
                    themeIcon.className = 'icon-copy dw dw-sun';
                } else {
                    themeIcon.className = 'icon-copy dw dw-moon';
                }
            }
        }

        // Active page highlighting
        function highlightActivePage() {
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('#accordion-menu a');

            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPath) {
                    item.parentElement.classList.add('active');
                } else {
                    item.parentElement.classList.remove('active');
                }
            });
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
            initDragAndDrop();
            loadMenuOrder();
            initSidebarToggle();
            updateLanguageDisplay();
            highlightActivePage();
        });

        // Language Change Function
        function changeLanguage(lang) {
            // Update language via AJAX
            fetch('{{ route('supplier.settings.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        language: lang,
                        _token: '{{ csrf_token() }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the language display
                        document.querySelector('.language-text').textContent = lang.toUpperCase();

                        // Show success message
                        showNotification('Language changed successfully!', 'success');

                        // Reload page to apply language changes
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showNotification('Failed to change language', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error changing language', 'error');
                });
        }

        // Update language display
        function updateLanguageDisplay() {
            const currentLang = '{{ Auth::user()->language ?? 'en' }}';
            const langText = document.querySelector('.language-text');
            if (langText) {
                langText.textContent = currentLang.toUpperCase();
            }
        }

        // Show notification
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#28a745' : '#dc3545'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 9999;
                font-weight: 500;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;

            // Add animation styles
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);

            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Sidebar Toggle Functionality
        function initSidebarToggle() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');

            // Load saved sidebar state
            const isCollapsed = localStorage.getItem('supplier-sidebar-collapsed') === 'true';
            if (isCollapsed) {
                collapseSidebar();
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (sidebar.classList.contains('expanded')) {
                collapseSidebar();
            } else {
                expandSidebar();
            }
        }

        function collapseSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');

            sidebar.classList.remove('expanded');
            sidebar.classList.add('collapsed');
            mainContent.classList.add('sidebar-collapsed');
            toggleBtn.classList.add('rotated');

            // Hide text in menu items
            const menuTexts = document.querySelectorAll('.mtext');
            menuTexts.forEach(text => {
                text.style.opacity = '0';
                text.style.transform = 'translateX(-20px)';
            });

            localStorage.setItem('supplier-sidebar-collapsed', 'true');
        }

        function expandSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');

            sidebar.classList.remove('collapsed');
            sidebar.classList.add('expanded');
            mainContent.classList.remove('sidebar-collapsed');
            toggleBtn.classList.remove('rotated');

            // Show text in menu items
            const menuTexts = document.querySelectorAll('.mtext');
            menuTexts.forEach(text => {
                text.style.opacity = '1';
                text.style.transform = 'translateX(0)';
            });

            localStorage.setItem('supplier-sidebar-collapsed', 'false');
        }

        // Drag and Drop functionality for sidebar menu items
        function initDragAndDrop() {
            const menuItems = document.querySelectorAll('#accordion-menu li');

            menuItems.forEach(item => {
                item.draggable = true;

                item.addEventListener('dragstart', function(e) {
                    this.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/html', this.outerHTML);
                });

                item.addEventListener('dragend', function(e) {
                    this.classList.remove('dragging');
                });

                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    this.classList.add('drag-over');
                });

                item.addEventListener('dragleave', function(e) {
                    this.classList.remove('drag-over');
                });

                item.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');

                    const draggedItem = document.querySelector('.dragging');
                    if (draggedItem && draggedItem !== this) {
                        const parent = this.parentNode;
                        const nextSibling = this.nextSibling;

                        if (nextSibling) {
                            parent.insertBefore(draggedItem, nextSibling);
                        } else {
                            parent.appendChild(draggedItem);
                        }

                        // Save the new order to localStorage
                        saveMenuOrder();
                    }
                });
            });
        }

        // Save menu order to localStorage
        function saveMenuOrder() {
            const menuItems = Array.from(document.querySelectorAll('#accordion-menu li'));
            const order = menuItems.map(item => item.querySelector('a').getAttribute('href'));
            localStorage.setItem('supplier-menu-order', JSON.stringify(order));
        }

        // Load saved menu order
        function loadMenuOrder() {
            const savedOrder = localStorage.getItem('supplier-menu-order');
            if (savedOrder) {
                const order = JSON.parse(savedOrder);
                const menuContainer = document.getElementById('accordion-menu');
                const menuItems = Array.from(menuContainer.children);

                // Reorder items based on saved order
                order.forEach(href => {
                    const item = menuItems.find(li => li.querySelector('a').getAttribute('href') === href);
                    if (item) {
                        menuContainer.appendChild(item);
                    }
                });
            }
        }

        // Notification System
        function loadNotifications() {
            fetch('/supplier/notifications/unread-count', {
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
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error loading notification count:', error));
        }

        function loadNotificationList() {
            fetch('/supplier/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const markAllReadBtn = document.getElementById('markAllReadBtn');

                    if (data.count > 0) {
                        console.log('Showing mark all read button, count:', data.count);
                        markAllReadBtn.style.display = 'block';
                        notificationList.innerHTML = `
                        <div class="p-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-bell text-primary me-2"></i>
                                <span>You have ${data.count} unread notification${data.count > 1 ? 's' : ''}</span>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('supplier.notifications') }}" class="btn btn-sm btn-primary">View All Notifications</a>
                            </div>
                        </div>
                    `;
                    } else {
                        console.log('Hiding mark all read button, count:', data.count);
                        markAllReadBtn.style.display = 'none';
                        notificationList.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <i class="fa fa-bell-slash fa-2x mb-2"></i>
                            <p>No notifications</p>
                        </div>
                    `;
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        function markNotificationAsRead(notificationId) {
            fetch(`/supplier/notifications/${notificationId}/mark-read`, {
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
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
        }

        function markAllNotificationsAsRead() {
            console.log('Marking all notifications as read...');

            fetch('/supplier/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Mark all read response:', data);
                    if (data.success) {
                        console.log('Successfully marked all notifications as read');
                        console.log('Updated count:', data.updated_count);
                        console.log('Unread count before:', data.unread_count_before);

                        // Hide the mark all read button immediately
                        const markAllReadBtn = document.getElementById('markAllReadBtn');
                        if (markAllReadBtn) {
                            markAllReadBtn.style.display = 'none';
                        }

                        // Update the notification list to show success message first
                        const notificationList = document.getElementById('notificationList');
                        if (notificationList) {
                            const message = data.updated_count > 0 ?
                                `Marked ${data.updated_count} notification${data.updated_count > 1 ? 's' : ''} as read!` :
                                'All notifications marked as read!';

                            notificationList.innerHTML = `
                                <div class="text-center text-success py-3">
                                    <i class="fa fa-check-circle fa-2x mb-2"></i>
                                    <p>${message}</p>
                                </div>
                            `;

                            // After 2 seconds, show "no notifications"
                            setTimeout(() => {
                                notificationList.innerHTML = `
                                    <div class="text-center text-muted py-3">
                                        <i class="fa fa-bell-slash fa-2x mb-2"></i>
                                        <p>No notifications</p>
                                    </div>
                                `;
                            }, 2000);
                        }

                        // Update the badge count
                        loadNotifications();

                        // Also call loadNotificationList to ensure consistency
                        setTimeout(() => {
                            loadNotificationList();
                        }, 100);
                    } else {
                        console.error('Failed to mark all notifications as read:', data);
                        alert('Failed to mark notifications as read. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                    alert('Error marking notifications as read: ' + error.message);
                });
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();

            // Set up notification dropdown
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown) {
                notificationDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadNotificationList();
                });
            }

            // Set up mark all read button using event delegation
            document.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'markAllReadBtn') {
                    e.preventDefault();
                    console.log('Mark all read button clicked');
                    markAllNotificationsAsRead();
                }
            });
        });

        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
    </script>

    @stack('scripts')
</body>

</html>
