# 📧 CMail Usage Guide - Complete Email System

## ✅ **CMail Class Enhanced with New Methods!**

### **🔧 Available CMail Methods:**

#### **1. Basic Email Sending:**
```php
// Send email with custom configuration
$config = [
    'recipient_address' => 'user@example.com',
    'recipient_name' => 'John Doe',
    'subject' => 'Test Email',
    'body' => '<h1>Hello World!</h1>'
];

$result = \App\Helpers\CMail::send($config);
```

#### **2. Template Email:**
```php
// Send email using Blade template
$result = \App\Helpers\CMail::sendTemplate(
    'user@example.com',
    'Welcome Email',
    'email-templates.welcome',
    [
        'name' => 'John Doe',
        'actionlink' => 'https://example.com/verify',
        'expiry_minutes' => 60
    ]
);
```

#### **3. Text Email:**
```php
// Send simple text email
$result = \App\Helpers\CMail::sendText(
    'user@example.com',
    'Simple Message',
    'This is a plain text email.'
);
```

#### **4. Email with Attachments:**
```php
// Send email with file attachments
$config = [
    'recipient_address' => 'user@example.com',
    'recipient_name' => 'John Doe',
    'subject' => 'Document Attached',
    'body' => '<p>Please find the attached document.</p>'
];

$attachments = [
    '/path/to/document.pdf',
    ['path' => '/path/to/image.jpg', 'name' => 'photo.jpg']
];

$result = \App\Helpers\CMail::sendWithAttachment($config, $attachments);
```

#### **5. Bulk Email:**
```php
// Send bulk emails to multiple recipients
$recipients = [
    ['name' => 'John Doe', 'email' => 'john@example.com'],
    ['name' => 'Jane Smith', 'email' => 'jane@example.com']
];

$result = \App\Helpers\CMail::sendBulk(
    $recipients,
    'Newsletter',
    'email-templates.newsletter',
    ['content' => 'Latest updates...']
);
```

#### **6. Configuration Testing:**
```php
// Test SMTP configuration without sending email
$result = \App\Helpers\CMail::testConfiguration();

// Get configuration status
$status = \App\Helpers\CMail::getConfigStatus();
```

### **🧪 Test Routes Available:**

#### **1. Test Configuration:**
```bash
curl http://localhost:8000/test-cmail-config
```

#### **2. Test Configuration Status:**
```bash
curl http://localhost:8000/test-cmail-status
```

#### **3. Test Template Email:**
```bash
curl http://localhost:8000/test-cmail-template
```

#### **4. Test Text Email:**
```bash
curl http://localhost:8000/test-cmail-text
```

#### **5. Test Bulk Email:**
```bash
curl http://localhost:8000/test-cmail-bulk
```

### **📋 Usage Examples in Controllers:**

#### **Password Reset Email:**
```php
// In AuthController.php
$result = \App\Helpers\CMail::sendTemplate(
    $user->email,
    'Password Reset',
    'email-templates.forgot-template',
    [
        'name' => $user->name,
        'actionlink' => $resetLink,
        'expiry_minutes' => 60
    ]
);
```

#### **Contact Inquiry Email:**
```php
// In UserController.php
$result = \App\Helpers\CMail::sendTemplate(
    'info@gnraw.com',
    'New Contact Inquiry',
    'email-templates.contact-inquiry',
    [
        'inquiry' => $inquiry,
        'product' => $product
    ]
);
```

#### **Welcome Email:**
```php
// Send welcome email to new user
$result = \App\Helpers\CMail::sendTemplate(
    $user->email,
    'Welcome to Genuine Nutra',
    'email-templates.welcome',
    [
        'name' => $user->name,
        'login_url' => route('login')
    ]
);
```

### **🔧 Configuration Requirements:**

#### **Environment Variables (.env):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_actual_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@gnraw.com
MAIL_FROM_NAME="Genuine Nutra"
```

### **📊 Error Handling:**

#### **All methods return:**
- `true` - Email sent successfully
- `false` - Email failed to send

#### **Bulk email returns:**
```php
[
    'total' => 10,
    'success' => 8,
    'failed' => 2,
    'results' => [
        ['email' => 'user1@example.com', 'success' => true],
        ['email' => 'user2@example.com', 'success' => false]
    ]
]
```

### **📝 Logging:**

All CMail methods log their activities:
- **Success**: `CMail sent successfully to: user@example.com`
- **Failure**: `CMail send failed: Error message`
- **Configuration**: `CMail Configuration: {...}`

### **🎯 Best Practices:**

1. **Always check return values** for success/failure
2. **Use templates** for consistent email design
3. **Handle errors gracefully** with try-catch blocks
4. **Log important email activities** for debugging
5. **Test configuration** before sending bulk emails
6. **Use appropriate delays** for bulk email sending

### **🚀 Ready to Use:**

Your CMail system now includes:
- ✅ **Basic email sending**
- ✅ **Template-based emails**
- ✅ **Text emails**
- ✅ **Email with attachments**
- ✅ **Bulk email sending**
- ✅ **Configuration testing**
- ✅ **Comprehensive logging**
- ✅ **Error handling**

**CMail is now a complete email solution for your Laravel application! 📧**
