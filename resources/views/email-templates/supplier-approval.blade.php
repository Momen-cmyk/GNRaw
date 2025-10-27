@php
    $settings = \App\Models\GeneralSetting::first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Application Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #27ae60;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        .content {
            margin-bottom: 30px;
        }

        .welcome-section {
            background-color: #e8f5e8;
            border: 1px solid #c3e6c3;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .next-steps {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .next-steps h3 {
            color: #0c5460;
            margin-top: 0;
        }

        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .next-steps li {
            margin: 8px 0;
        }

        .contact-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-success {
            background-color: #27ae60;
        }

        .btn-success:hover {
            background-color: #219a52;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ $settings->site_title ?? 'GNRAW' }}</div>
            <div class="status-badge">Application Approved</div>
        </div>

        <div class="content">
            <h2>Congratulations {{ $supplier->name }}!</h2>

            <p>We are pleased to inform you that your supplier application has been <strong>approved</strong>! Welcome
                to the {{ $settings->site_title ?? 'GNRAW' }} supplier network.</p>

            <div class="welcome-section">
                <h3 style="color: #27ae60; margin-top: 0;">🎉 Welcome to {{ $settings->site_title ?? 'GNRAW' }}!</h3>
                <p>Your company <strong>{{ $supplier->company_name }}</strong> has been successfully approved as a
                    supplier. You can now start managing your products and receiving inquiries from potential clients.
                </p>
            </div>

            <div class="next-steps">
                <h3>What You Can Do Next:</h3>
                <ul>
                    <li><strong>Access your dashboard:</strong> Log in to your supplier account to manage your profile
                    </li>
                    <li><strong>Add products:</strong> Start adding your products to our marketplace</li>
                    <li><strong>Upload documents:</strong> Ensure all required certificates are uploaded and verified
                    </li>
                    <li><strong>Set up notifications:</strong> Configure your notification preferences</li>
                    <li><strong>Complete your profile:</strong> Add any missing information to your supplier profile
                    </li>
                </ul>
            </div>

            <p>We're excited to work with you and look forward to a successful partnership. If you have any questions or
                need assistance getting started, please don't hesitate to contact our support team.</p>
        </div>

        <div class="contact-info">
            <h3>Need Help Getting Started?</h3>
            <p>Our support team is here to help you make the most of your supplier account:</p>
            <p>
                <strong>Email:</strong> {{ $settings->site_email ?? '' }}<br>
                <strong>Phone:</strong> {{ $settings->site_phone ?? '' }}<br>
                <strong>Website:</strong> <a
                    href="{{ $settings->site_website ?? '' }}">{{ $settings->site_website ?? '' }}</a>
            </p>
            <a href="{{ route('supplier.login') }}" class="btn btn-success">Access Your Dashboard</a>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} GNRAW. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
