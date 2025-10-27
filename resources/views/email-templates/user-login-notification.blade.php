@php
    $settings = \App\Models\GeneralSetting::first();
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Notification - {{ $settings->site_title ?? 'GNRAW' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }

        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🔐 Login Notification</h1>
        <p>{{ $settings->site_title ?? 'GNRAW' }} Security Alert</p>
    </div>

    <div class="content">
        <h2>Hello {{ $user->name }}!</h2>

        <div class="info-box">
            <h3>📱 New Login Detected</h3>
            <p><strong>Time:</strong> {{ $loginTime ?? now()->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>IP Address:</strong> {{ $ipAddress ?? 'Unknown' }}</p>
            <p><strong>Device:</strong> {{ $device ?? 'Unknown Device' }}</p>
            <p><strong>Location:</strong> {{ $location ?? 'Unknown Location' }}</p>
        </div>

        @if ($isNewDevice)
            <div class="warning-box">
                <h3>⚠️ New Device Alert</h3>
                <p>This appears to be a new device or browser. If this wasn't you, please secure your account
                    immediately.</p>
            </div>
        @endif

        <h3>🛡️ Security Tips:</h3>
        <ul>
            <li>Always log out from shared computers</li>
            <li>Use strong, unique passwords</li>
            <li>Enable two-factor authentication if available</li>
            <li>Report suspicious activity immediately</li>
        </ul>

        <p>If you didn't make this login, please contact our support team immediately.</p>

        <a href="{{ $settings->site_website ?? '' }}/contact" class="btn">Contact Support</a>
    </div>

    <div class="footer">
        <p>This is an automated security notification from {{ $settings->site_title ?? 'GNRAW' }}</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>

</html>
