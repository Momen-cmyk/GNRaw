# 📧 CMail Configuration Explained

## ❌ **There are NO `CMAIL_*` variables in .env!**

### **🔍 Why You Can't Find `CMAIL_*` Variables:**

The CMail class **does NOT use** separate `CMAIL_*` environment variables. Instead, it uses **Laravel's standard mail configuration**.

### **📋 Current Configuration Mapping:**

| **What You're Looking For** | **Actual .env Variable** | **Current Value**  |
| --------------------------- | ------------------------ | ------------------ |
| `CMAIL_HOST`                | `MAIL_HOST`              | `smtp.titan.email` |
| `CMAIL_PORT`                | `MAIL_PORT`              | `587`              |
| `CMAIL_USERNAME`            | `MAIL_USERNAME`          | `info@gnraw.com`   |
| `CMAIL_PASSWORD`            | `MAIL_PASSWORD`          | `Riw@n55555`       |
| `CMAIL_ENCRYPTION`          | `MAIL_ENCRYPTION`        | `tls`              |

### **🔧 How CMail Reads Configuration:**

```php
// In CMail.php - these are the actual config calls:
$mail->Host = config('mail.mailers.smtp.host');        // ← MAIL_HOST
$mail->Port = config('mail.mailers.smtp.port');        // ← MAIL_PORT
$mail->Username = config('mail.mailers.smtp.username'); // ← MAIL_USERNAME
$mail->Password = config('mail.mailers.smtp.password'); // ← MAIL_PASSWORD
$mail->SMTPSecure = config('mail.mailers.smtp.encryption'); // ← MAIL_ENCRYPTION
```

### **📁 Your Current .env Configuration:**

```env
# Titan Email Configuration - Trying STARTTLS
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.titan.email          # ← This is your "CMAIL_HOST"
MAIL_PORT=587                       # ← This is your "CMAIL_PORT"
MAIL_USERNAME=info@gnraw.com        # ← This is your "CMAIL_USERNAME"
MAIL_PASSWORD=Riw@n55555           # ← This is your "CMAIL_PASSWORD"
MAIL_ENCRYPTION=tls                # ← This is your "CMAIL_ENCRYPTION"
MAIL_FROM_ADDRESS=info@gnraw.com
MAIL_FROM_NAME="Genuine Nutra"
```

### **🧪 Test Your Current Configuration:**

#### **1. Check Configuration Status:**

```bash
curl http://localhost:8000/test-cmail-status
```

#### **2. Test SMTP Connection:**

```bash
curl http://localhost:8000/test-cmail-config
```

#### **3. Test Email Sending:**

```bash
curl http://localhost:8000/test-cmail-text
```

### **🔍 Troubleshooting Authentication Issues:**

#### **Current Error: "Could not authenticate"**

This means the SMTP server is rejecting your credentials. Possible causes:

1. **Wrong Password** - Double-check `Riw@n55555`
2. **Wrong Username** - Verify `info@gnraw.com` is correct
3. **Wrong SMTP Server** - Confirm `smtp.titan.email` is correct
4. **Wrong Port/Encryption** - Try different combinations

### **🔄 Try Different Configurations:**

#### **Option 1: SSL (Port 465)**

```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

#### **Option 2: STARTTLS (Port 587) - Current**

```env
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

#### **Option 3: Different SMTP Server**

```env
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### **📧 Verify Your Email Credentials:**

#### **1. Check Email Account:**

-   Login to `info@gnraw.com` via webmail
-   Verify the password `Riw@n55555` works
-   Check if 2FA is enabled (might need app password)

#### **2. Check SMTP Settings:**

-   Confirm `smtp.titan.email` is the correct server
-   Verify port 587 with TLS is correct
-   Check if your hosting provider blocks SMTP

### **🧪 Debug Steps:**

#### **1. Enable SMTP Debug:**

The CMail class already has debug logging. Check:

```bash
tail -f storage/logs/laravel.log
```

#### **2. Test with Different Email Client:**

Try sending from the same credentials using:

-   Outlook
-   Thunderbird
-   Gmail (if you can add the account)

#### **3. Contact Your Email Provider:**

-   Ask for correct SMTP settings
-   Verify if SMTP is enabled for your account
-   Check if there are any restrictions

### **✅ Summary:**

-   **NO `CMAIL_*` variables exist** - CMail uses Laravel's `MAIL_*` variables
-   **Your configuration is correct** - all variables are properly set
-   **Authentication issue** - likely password, server, or port problem
-   **Try different combinations** - SSL vs STARTTLS, different ports
-   **Verify credentials** - make sure email account works

### **🎯 Next Steps:**

1. **Verify your email password** with your email provider
2. **Try the SSL configuration** (port 465)
3. **Check with Titan Email support** for correct SMTP settings
4. **Test with a different email client** to isolate the issue

**The configuration is correct - it's an authentication problem! 🔧**
