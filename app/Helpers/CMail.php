<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class CMail
{
    public static function send($config, $reply = false)
    {
        $mail = new PHPMailer(true);

        try {
            // Enable verbose debug output in development
            $mail->SMTPDebug = config('app.debug') ? 2 : 0;

            // Server settings
            $mail->isSMTP();
            $mail->Host       = config('mail.mailers.smtp.host');
            $mail->SMTPAuth   = true;
            $mail->Username   = config('mail.mailers.smtp.username');
            $mail->Password   = config('mail.mailers.smtp.password');
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption') ?: 'ssl';
            $mail->Port       = config('mail.mailers.smtp.port');
            $mail->Timeout    = 60; // Increased timeout for Hostinger
            $mail->SMTPKeepAlive = true; // Keep connection alive

            // Log configuration for debugging
            Log::info('CMail Configuration:', [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'username' => config('mail.mailers.smtp.username'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name')
            ]);

            // Recipients
            $mail->setFrom(
                config('mail.from.address'),
                config('mail.from.name')
            );

            $mail->addAddress(
                $config['recipient_address'],
                isset($config['recipient_name']) ? $config['recipient_name'] : null
            );

            if ($reply) {
                $mail->addReplyTo(
                    isset($config['reply_to_address']) ? $config['reply_to_address'] : config('mail.from.address'),
                    isset($config['reply_to_name']) ? $config['reply_to_name'] : config('mail.from.name')
                );
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $config['subject'];
            $mail->Body = $config['body'];

            // Add plain text version
            $mail->AltBody = strip_tags($config['body']);

            // Send the email
            $result = $mail->send();

            if ($result) {
                Log::info('CMail sent successfully to: ' . $config['recipient_address']);
                return true;
            } else {
                Log::error('CMail send() returned false for: ' . $config['recipient_address']);
                return false;
            }
        } catch (Exception $e) {
            Log::error('CMail send failed: ' . $e->getMessage(), [
                'recipient' => $config['recipient_address'] ?? 'unknown',
                'subject' => $config['subject'] ?? 'unknown',
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);
            return false;
        } finally {
            // Clean up
            $mail->smtpClose();
        }
    }

    /**
     * Test SMTP configuration without sending an email
     */
    public static function testConfiguration()
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.mailers.smtp.username');
            $mail->Password = config('mail.mailers.smtp.password');
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption') ?: 'ssl';
            $mail->Port = config('mail.mailers.smtp.port');
            $mail->Timeout = 30;

            // Test connection
            $result = $mail->smtpConnect();

            if ($result) {
                Log::info('CMail configuration test: SUCCESS');
                return [
                    'success' => true,
                    'message' => 'SMTP configuration is valid',
                    'config' => [
                        'host' => config('mail.mailers.smtp.host'),
                        'port' => config('mail.mailers.smtp.port'),
                        'encryption' => config('mail.mailers.smtp.encryption'),
                        'username' => config('mail.mailers.smtp.username'),
                        'from_address' => config('mail.from.address'),
                        'from_name' => config('mail.from.name')
                    ]
                ];
            } else {
                Log::error('CMail configuration test: FAILED - Could not connect');
                return [
                    'success' => false,
                    'message' => 'Could not connect to SMTP server'
                ];
            }
        } catch (Exception $e) {
            Log::error('CMail configuration test failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Configuration test failed: ' . $e->getMessage()
            ];
        } finally {
            $mail->smtpClose();
        }
    }

    /**
     * Send HTML email with template
     */
    public static function sendTemplate($to, $subject, $template, $data = [], $reply = false)
    {
        try {
            $mail_body = view($template, $data)->render();

            $config = [
                'recipient_address' => $to,
                'recipient_name' => $data['name'] ?? 'User',
                'subject' => $subject,
                'body' => $mail_body
            ];

            return self::send($config, $reply);
        } catch (\Exception $e) {
            Log::error('CMail sendTemplate failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send simple text email
     */
    public static function sendText($to, $subject, $message, $reply = false)
    {
        try {
            $config = [
                'recipient_address' => $to,
                'recipient_name' => 'User',
                'subject' => $subject,
                'body' => $message
            ];

            return self::send($config, $reply);
        } catch (\Exception $e) {
            Log::error('CMail sendText failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email with attachments
     */
    public static function sendWithAttachment($config, $attachments = [], $reply = false)
    {
        $mail = new PHPMailer(true);

        try {
            // Enable verbose debug output in development
            $mail->SMTPDebug = config('app.debug') ? 2 : 0;

            // Server settings
            $mail->isSMTP();
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.mailers.smtp.username');
            $mail->Password = config('mail.mailers.smtp.password');
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption') ?: 'ssl';
            $mail->Port = config('mail.mailers.smtp.port');
            $mail->Timeout = 60;
            $mail->SMTPKeepAlive = true;

            // Recipients
            $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mail->addAddress($config['recipient_address'], $config['recipient_name'] ?? null);

            if ($reply) {
                $mail->addReplyTo(config('mail.from.address'), config('mail.from.name'));
            }

            // Add attachments
            foreach ($attachments as $attachment) {
                if (is_array($attachment)) {
                    $mail->addAttachment($attachment['path'], $attachment['name'] ?? '');
                } else {
                    $mail->addAttachment($attachment);
                }
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $config['subject'];
            $mail->Body = $config['body'];
            $mail->AltBody = strip_tags($config['body']);

            $result = $mail->send();

            if ($result) {
                Log::info('CMail with attachments sent successfully to: ' . $config['recipient_address']);
                return true;
            } else {
                Log::error('CMail with attachments send() returned false for: ' . $config['recipient_address']);
                return false;
            }
        } catch (Exception $e) {
            Log::error('CMail with attachments send failed: ' . $e->getMessage());
            return false;
        } finally {
            $mail->smtpClose();
        }
    }

    /**
     * Send bulk emails
     */
    public static function sendBulk($recipients, $subject, $template, $data = [], $reply = false)
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($recipients as $recipient) {
            $emailData = array_merge($data, [
                'name' => $recipient['name'] ?? 'User',
                'email' => $recipient['email']
            ]);

            $result = self::sendTemplate($recipient['email'], $subject, $template, $emailData, $reply);

            $results[] = [
                'email' => $recipient['email'],
                'success' => $result
            ];

            if ($result) {
                $successCount++;
            } else {
                $failureCount++;
            }

            // Small delay between emails to avoid rate limiting
            usleep(100000); // 0.1 second delay
        }

        Log::info("CMail bulk send completed: {$successCount} success, {$failureCount} failed");

        return [
            'total' => count($recipients),
            'success' => $successCount,
            'failed' => $failureCount,
            'results' => $results
        ];
    }

    /**
     * Get email configuration status
     */
    public static function getConfigStatus()
    {
        return [
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'password_set' => !empty(config('mail.mailers.smtp.password')),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'timeout' => 60,
            'keep_alive' => true
        ];
    }
}
