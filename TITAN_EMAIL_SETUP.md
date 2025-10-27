# 📧 Titan Email Setup Guide

## ✅ **Email Configuration Updated for Titan Email!**

### **🔧 Current Configuration:**

#### **Primary Settings (SSL - Port 465):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.titan.email
MAIL_PORT=465
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_actual_email_password_here
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@gnraw.com
MAIL_FROM_NAME="Genuine Nutra"
```

#### **Alternative Settings (STARTTLS - Port 587):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.titan.email
MAIL_PORT=587
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_actual_email_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@gnraw.com
MAIL_FROM_NAME="Genuine Nutra"
```

### **📝 Next Steps:**

#### **1. Update Your Email Password:**

In your `.env` file, change line 61:

```env
MAIL_PASSWORD=your_actual_email_password_here
```

To your real Titan email password:

```env
MAIL_PASSWORD=your_real_titan_password
```

#### **2. Test the Configuration:**

```bash
# Test SMTP configuration
curl http://localhost:8000/test-cmail-config

# Test configuration status
curl http://localhost:8000/test-cmail-status

# Test email sending
curl http://localhost:8000/test-password-reset-email
```

### **🧪 Test Results Expected:**

#### **After updating password, you should see:**

```json
{
    "success": true,
    "message": "SMTP configuration is valid",
    "config": {
        "host": "smtp.titan.email",
        "port": 465,
        "encryption": "ssl",
        "username": "info@gnraw.com",
        "from_address": "info@gnraw.com",
        "from_name": "Genuine Nutra"
    }
}
```

### **🔧 Titan Email Settings Summary:**

| Setting             | Value            | Purpose                         |
| ------------------- | ---------------- | ------------------------------- |
| **SMTP Server**     | smtp.titan.email | Titan's outgoing mail server    |
| **Port (SSL)**      | 465              | Secure SSL connection           |
| **Port (STARTTLS)** | 587              | Alternative STARTTLS connection |
| **Encryption**      | SSL or TLS       | Secure email transmission       |
| **Username**        | info@gnraw.com   | Your email address              |
| **Password**        | [Your Password]  | Your email password             |

### **📊 Configuration Options:**

#### **Option 1: SSL (Port 465) - Recommended**

-   **More secure** and widely supported
-   **Faster connection** establishment
-   **Better for production** environments

#### **Option 2: STARTTLS (Port 587) - Alternative**

-   **Good for restricted networks** that block port 465
-   **Same security level** as SSL
-   **May be slower** due to upgrade process

### **🔍 Troubleshooting:**

#### **If SSL (465) doesn't work:**

1. **Switch to STARTTLS (587):**

    ```env
    MAIL_PORT=587
    MAIL_ENCRYPTION=tls
    ```

2. **Test the new configuration:**
    ```bash
    curl http://localhost:8000/test-cmail-config
    ```

#### **Common Issues:**

1. **"Could not authenticate"** - Wrong password
2. **"Connection refused"** - Wrong port or host
3. **"SSL/TLS Error"** - Wrong encryption setting

### **📧 Email Features Available:**

#### **1. Password Reset Emails:**

-   Uses `forgot-template` email template
-   Sent to user's email address
-   Includes secure reset link

#### **2. Contact Inquiry Emails:**

-   Uses `contact-inquiry` email template
-   Sent to `info@gnraw.com`
-   Includes inquiry details

#### **3. Product Inquiry Emails:**

-   Uses `InquiryReceivedNotification`
-   Sent to `info@gnraw.com`
-   Includes product and customer details

#### **4. Test Emails:**

-   Multiple test routes available
-   Template and text email testing
-   Bulk email testing

### **🚀 Production Deployment:**

#### **For Production Upload:**

1. **Update password** in `.env` file
2. **Test configuration** locally
3. **Upload to server** with correct settings
4. **Test on production** environment

#### **Production Environment Variables:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gnraw.com
MAIL_HOST=smtp.titan.email
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_production_password
```

### **✅ Ready to Use:**

Your email system is now configured for:

-   ✅ **Titan Email SMTP** server
-   ✅ **SSL encryption** (port 465)
-   ✅ **Alternative STARTTLS** (port 587)
-   ✅ **All CMail methods** available
-   ✅ **Comprehensive testing** routes
-   ✅ **Production ready** configuration

**Just update the password and test! 🚀**
