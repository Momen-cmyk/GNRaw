@php
    $settings = \App\Models\GeneralSetting::first();
@endphp
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f4f6f8;padding:30px 10px;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                style="background:#ffffff;border-radius:8px;overflow:hidden;max-width:600px;">

                <!-- Header -->
                <tr>
                    <td style="padding:24px 24px 8px;text-align:center;font-family:Arial,Helvetica,sans-serif;">
                        <!-- Header Section -->
                        <div style="text-align: center; margin-bottom: 20px;">
                            <div
                                style="font-size: 28px; font-weight: bold; color: #2563eb; margin-bottom: 5px; text-align: center; letter-spacing: 1px;">
                                {{ $settings->site_title ?? 'GNRAW' }}
                            </div>
                            <div style="font-size: 14px; color: #666; text-align: center; margin-bottom: 15px;">
                                Professional Nutrition Solutions
                            </div>
                        </div>
                        <h1 style="margin:0;font-size:20px;color:#111827;">Reset your password</h1>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td
                        style="padding:16px 24px 24px;font-family:Arial,Helvetica,sans-serif;color:#374151;line-height:1.6;">
                        <p style="margin:0 0 12px;">Hi {{ $user->name }},</p>

                        <!-- Button -->
                        <table role="presentation" cellpadding="0" cellspacing="0"
                            style="margin:18px auto;width:100%;max-width:280px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ $actionlink }}" target="_blank"
                                        style="display:block;width:100%;padding:14px 0;background:#2563eb;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;font-family:Arial,Helvetica,sans-serif;text-align:center;">
                                        Reset password
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 12px;text-align:center;color:#6b7280;font-size:13px;">
                            This link will expire in 15 minutes.
                        </p>

                        <p style="margin:16px 0 12px;">If the button doesn't work, copy and paste this link into your
                            browser:</p>

                        <p style="word-break:break-all;margin:0 0 16px;color:#1f2937;font-size:13px;">
                            <a href="{{ $actionlink }}" style="color:#2563eb;text-decoration:underline;">
                                {{ $actionlink }}
                            </a>
                        </p>

                        <p style="margin:0 0 8px;color:#6b7280;font-size:13px;">
                            If you didn't request a password reset, you can safely ignore this email — your password
                            won't change.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td
                        style="padding:12px 24px 24px;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#9ca3af;text-align:center;">
                        <p style="margin:0;">&copy;{{ date('Y') }} Thanks,<br>The
                            <strong>{{ $settings->site_title ?? 'GNRAW' }}</strong> team
                        </p>
                        <p style="margin:8px 0 0;color:#d1d5db;font-size:12px;">If you need help, reply to this email or
                            contact {{ $settings->site_email ?? 'info@gnraw.com' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Responsive tweak for mobile -->
<style>
    @media only screen and (max-width: 520px) {
        table[role="presentation"] {
            width: 100% !important;
        }

        h1 {
            font-size: 18px !important;
        }

        img {
            width: 100px !important;
            height: auto !important;
        }

        a {
            font-size: 16px !important;
        }
    }
</style>
