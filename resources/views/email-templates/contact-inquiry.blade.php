@php
    $settings = \App\Models\GeneralSetting::first();
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Inquiry - {{ $settings->site_title ?? 'GNRAW' }}</title>
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

        .customer-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .message-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #dee2e6;
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
        <h1>📧 New Contact Inquiry</h1>
        <p>{{ $settings->site_title ?? 'GNRAW' }} Contact Form</p>
    </div>

    <div class="content">
        <h2>You have received a new inquiry!</h2>

        <div class="info-box">
            <h3>📋 Inquiry Details</h3>
            <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
            <p><strong>Inquiry ID:</strong> #{{ $inquiry->id }}</p>
            <p><strong>Date:</strong> {{ $inquiry->inquiry_date->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($inquiry->status) }}</p>
        </div>

        <div class="customer-info">
            <h3>👤 Customer Information</h3>
            <p><strong>Name:</strong> {{ $inquiry->name }}</p>
            <p><strong>Email:</strong> {{ $inquiry->email }}</p>
            @if ($inquiry->phone)
                <p><strong>Phone:</strong> {{ $inquiry->phone }}</p>
            @endif
            @if ($inquiry->company)
                <p><strong>Company:</strong> {{ $inquiry->company }}</p>
            @endif
        </div>

        @if ($product)
            <div class="info-box">
                <h3>🛍️ Related Product</h3>
                <p><strong>Product:</strong> {{ $product->name }}</p>
                <p><strong>Product ID:</strong> #{{ $product->id }}</p>
                @if ($product->description)
                    <p><strong>Description:</strong> {{ Str::limit($product->description, 100) }}</p>
                @endif
            </div>
        @endif

        <div class="message-box">
            <h3>💬 Customer Message</h3>
            <p>{{ $inquiry->message }}</p>
        </div>

        <div style="text-align: center;">
            <a href="mailto:{{ $inquiry->email }}" class="btn">Reply to Customer</a>
            <a href="{{ $settings->site_website ?? '' }}/admin/inquiries" class="btn">View
                in Admin Panel</a>
        </div>

        <h3>📝 Next Steps:</h3>
        <ul>
            <li>Review the customer's inquiry</li>
            <li>Reply to the customer via email</li>
            <li>Update inquiry status in admin panel</li>
            <li>Follow up if needed</li>
        </ul>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ $settings->site_title ?? 'GNRAW' }}</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>

</html>
