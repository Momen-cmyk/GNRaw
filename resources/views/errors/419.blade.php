<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Page Expired | {{ config('app.name', 'GNRAW') }}</title>

    <!-- Favicon -->
    @if (isset(settings()->site_favicon) && settings()->site_favicon)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/site/' . settings()->site_favicon) }}">
        <link rel="icon" type="image/png" sizes="16x16"
            href="{{ asset('images/site/' . settings()->site_favicon) }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/site/' . settings()->site_favicon) }}">
    @endif

    <!-- Bootstrap CSS -->
    <link href="{{ asset('back/src/plugins/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('back/src/fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-icon {
            font-size: 4rem;
            color: #ff6b6b;
            margin-bottom: 1.5rem;
        }

        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-refresh {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 10px 28px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            margin-left: 1rem;
        }

        .btn-refresh:hover {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            .error-container {
                padding: 2rem;
                margin: 1rem;
            }

            .error-code {
                font-size: 4rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .btn-refresh {
                margin-left: 0;
                margin-top: 1rem;
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fa fa-clock"></i>
        </div>
        <div class="error-code">419</div>
        <h1 class="error-title">Page Expired</h1>
        <p class="error-message">
            Your session has expired or the CSRF token is invalid. This usually happens when a form is submitted after
            being open for too long.
        </p>
        <div class="d-flex flex-column flex-md-row justify-content-center">
            <a href="{{ url()->previous() }}" class="btn-home">
                <i class="fa fa-arrow-left"></i> Go Back
            </a>
            <a href="{{ route('home') }}" class="btn-refresh">
                <i class="fa fa-home"></i> Go Home
            </a>
        </div>
    </div>
</body>

</html>
