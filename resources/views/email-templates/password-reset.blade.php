@php
    $settings = \App\Models\GeneralSetting::first();
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset - {{ $settings->site_title ?? 'GNRAW' }}</title>
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
            border-left: 4px solid #28a745;
        }

        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🔑 Password Reset</h1>
        <p>{{ $settings->site_title ?? 'GNRAW' }} Account Security</p>
    </div>

    <div class="content">
        <h2>Hello {{ $user->name ?? 'User' }}!</h2>

        <div class="info-box">
            <h3>🔄 Password Reset Request</h3>
            <p>We received a request to reset your password for your {{ $settings->site_title ?? 'GNRAW' }}account.</p>
            <p><strong>Account:</strong> {{ $user->email ?? 'Your Account' }}</p>
            <p><strong>Request Time:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <div class="warning-box">
            <h3>⚠️ Important Security Notice</h3>
            <p>If you didn't request this password reset, please ignore this email. Your password will remain unchanged.
            </p>
        </div>

        <h3>🔐 To Reset Your Password:</h3>
        <ol>
            <li>Click the "Reset Password" button below</li>
            <li>You'll be taken to a secure page</li>
            <li>Enter your new password</li>
            <li>Confirm your new password</li>
            <li>Click "Update Password"</li>
        </ol>

        <div style="text-align: center;">
            <a href="{{ $resetUrl ?? '#' }}" class="btn">Reset Password</a>
        </div>

        <div class="warning-box">
            <h3>⏰ Link Expiration</h3>
            <p>This password reset link will expire in <strong>60 minutes</strong> for security reasons.</p>
            <p>If the link expires, you can request a new password reset.</p>
        </div>

        <h3>🛡️ Security Tips:</h3>
        <ul>
            <li>Use a strong password with letters, numbers, and symbols</li>
            <li>Don't reuse passwords from other accounts</li>
            <li>Consider using a password manager</li>
            <li>Never share your password with anyone</li>
        </ul>

        <p>If you're having trouble with the button above, copy and paste the URL below into your web browser:</p>
        <p
            style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace;">
            {{ $resetUrl ?? 'Reset URL will be provided' }}
        </p>
    </div>

    <div class="footer">
        <p>This is an automated message from {{ $settings->site_title ?? 'GNRAW' }}</p>
        <p>If you didn't request this reset, please contact our support team</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>

</html>
