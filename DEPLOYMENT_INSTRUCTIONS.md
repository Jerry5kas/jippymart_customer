# 🚀 Production Deployment Instructions

## 🔴 CRITICAL PRIORITY: Security Fix

### Step 1: Deploy Security Files
Upload these files to your production server:

1. **storage/app/.htaccess** (already created)
2. **storage/app/firebase/.htaccess** (already created)

### Step 2: Test Security Fix
After deployment, test these URLs:

```bash
# Test 1: Firebase credentials should NOT be accessible
curl https://jippymart.in/storage/app/firebase/credentials.json
# Expected: 403 Forbidden or 404 Not Found
# If you get JSON data, the security fix failed!

# Test 2: Storage directory should NOT be accessible
curl https://jippymart.in/storage/app/
# Expected: 403 Forbidden or 404 Not Found
# If you get a directory listing, the security fix failed!
```

### Step 3: Test API Functionality
```bash
# Test the health endpoint
curl https://jippymart.in/api/health

# Test the catering API
curl -X POST https://jippymart.in/api/catering/requests \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "mobile": "9876543210",
    "email": "test@example.com",
    "place": "Test Location",
    "date": "2025-12-25",
    "guests": 50,
    "function_type": "Wedding",
    "meal_preference": "veg"
  }'
```

## 🟡 IMPORTANT PRIORITY: Email Configuration

### Step 1: Add Email Settings to .env
Add these to your production `.env` file:

```bash
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-email@jippymart.in
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jippymart.in
MAIL_FROM_NAME="JippyMart Catering"
```

### Step 2: Test Email Configuration
```bash
# Clear config cache
php artisan config:clear

# Test email (optional)
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('your-email@example.com')->subject('Test'); });
```

## ⚡ PERFORMANCE PRIORITY: Queue System

### Step 1: Set Up Database Queue
```bash
# Create jobs table
php artisan queue:table
php artisan migrate

# Update .env
QUEUE_CONNECTION=database
```

### Step 2: Start Queue Worker
```bash
# Start queue worker (keep this running)
php artisan queue:work --verbose

# Or use supervisor/systemd for production
```

### Step 3: Test Performance
- API response time should be under 10 seconds
- Emails should be processed in background

## 📊 Monitoring

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Monitor Queue
```bash
php artisan queue:work --verbose
```

### Health Check
```bash
curl https://jippymart.in/api/health
```

## ✅ Success Criteria

- [ ] Firebase credentials are NOT accessible via HTTP
- [ ] API responds successfully (200 status)
- [ ] Response time under 10 seconds
- [ ] No 500 errors in logs
- [ ] Emails are being sent (check logs)
- [ ] Firebase data is being stored

## 🆘 Troubleshooting

### If API returns 500 error:
1. Check `storage/logs/laravel.log`
2. Verify Firebase credentials are readable by PHP
3. Check file permissions

### If response time is slow (>30 seconds):
1. Check queue configuration
2. Ensure queue worker is running
3. Check email configuration

### If emails are not sent:
1. Verify SMTP credentials
2. Check queue worker is running
3. Look for email errors in logs

---

**Next Steps After Security Fix:**
1. Deploy current code to production
2. Test security fix
3. Test API functionality
4. Configure email settings
5. Set up queue system
