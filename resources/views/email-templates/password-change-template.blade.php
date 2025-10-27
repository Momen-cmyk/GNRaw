@php
    $settings = \App\Models\GeneralSetting::first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed</title>


    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #4CAF50;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 20px;
        }

        .content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }

        .info-box {
            background: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin: 15px 0;
            font-size: 14px;
        }

        .footer {
            background: #f4f4f7;
            text-align: center;
            font-size: 12px;
            color: #888;
            padding: 15px;
        }

        @media screen and (max-width: 600px) {

            .content,
            .header,
            .footer {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            Password Changed Successfully
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>Your password has been successfully updated. Here are your login details:</p>

            <div class="info-box">
                <p><strong>Username / Email:</strong> {{ $user->email }} OR {{ $user->username }} </p>
                <p><strong>New Password:</strong> {{ $new_password }}</p>

            </div>

            <p>If you did not make this change, please reset your password immediately or contact support.</p>
            <p>Thank you,<br>The Support Team</p>
        </div>
        <div class="footer">
            &copy;{{ date('Y') }} {{ $settings->site_title ?? 'GNRAW' }}. All rights reserved.
        </div>
    </div>
</body>

</html>
