<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('pageTitle')</title>

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

    <!-- Flexible Logo Styles -->
    <style>
        .brand-logo img {
            max-height: 60px;
            max-width: 250px;
            height: auto;
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        /* Responsive adjustments for auth layout */
        @media (max-width: 768px) {
            .brand-logo img {
                max-height: 50px;
                max-width: 200px;
            }
        }

        @media (max-width: 480px) {
            .brand-logo img {
                max-height: 40px;
                max-width: 150px;
            }
        }

        /* Ensure the brand logo container is flexible */
        .brand-logo {
            display: flex;
            align-items: center;
        }

        .brand-logo a {
            display: inline-block;
            line-height: 0;
        }
    </style>

    @stack('stylesheets')
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="">
                    <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.svg' }}"
                        alt="Logo"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                    <div style="display: none; font-size: 24px; font-weight: bold; color: #007bff;">
                        GNRAW
                    </div>
                </a>


            </div>
            <div class="login-menu">
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="/back/vendors/images/login-page-img.png" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    @stack('scripts')
</body>

</html>
