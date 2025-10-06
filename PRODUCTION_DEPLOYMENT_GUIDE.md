# Production Deployment Guide for Catering API

## ⚠️ Critical Issues That Must Be Fixed Before Production

### 1. Firebase Configuration

**Required Environment Variables:**
```bash
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\nYour-Private-Key-Here\n-----END PRIVATE KEY-----\n"
FIREBASE_CLIENT_EMAIL=your-service-account@your-project.iam.gserviceaccount.com
FIREBASE_PRIVATE_KEY_ID=your-key-id
FIREBASE_CLIENT_ID=your-client-id
FIREBASE_CLIENT_X509_CERT_URL=https://www.googleapis.com/robot/v1/metadata/x509/your-service-account%40your-project.iam.gserviceaccount.com
```

**File Setup:**
1. Upload Firebase credentials file to `storage/app/firebase/credentials.json`
2. Set proper file permissions: `chmod 644 storage/app/firebase/credentials.json`
3. Ensure web server can read the file

### 2. Email Configuration

**Required Environment Variables:**
```bash
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Your Company Name"
```

### 3. Queue System Configuration

**Option A: Database Queue (Recommended)**
```bash
QUEUE_CONNECTION=database
```
Then run:
```bash
php artisan queue:table
php artisan migrate
```

**Option B: Redis Queue (Better Performance)**
```bash
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Option C: Sync Queue (NOT RECOMMENDED - Will cause slow response times)**
```bash
QUEUE_CONNECTION=sync
```

### 4. File Permissions

**Critical Directories:**
```bash
# Make storage directories writable
chmod -R 775 storage/
chown -R www-data:www-data storage/

# Ensure Firebase credentials are readable
chmod 644 storage/app/firebase/credentials.json
chown www-data:www-data storage/app/firebase/credentials.json
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate

# If using database queue
php artisan queue:table
php artisan migrate
```

## Pre-Deployment Checklist

### Environment Variables
- [ ] `FIREBASE_PROJECT_ID` is set
- [ ] `FIREBASE_PRIVATE_KEY` is set (with proper newline characters)
- [ ] `FIREBASE_CLIENT_EMAIL` is set
- [ ] `MAIL_HOST` is set
- [ ] `MAIL_USERNAME` is set
- [ ] `MAIL_PASSWORD` is set
- [ ] `QUEUE_CONNECTION` is set (not 'sync')

### File System
- [ ] `storage/app/firebase/credentials.json` exists and is readable
- [ ] `storage/logs/` is writable
- [ ] `storage/app/` is writable

### Database
- [ ] All migrations have been run
- [ ] Jobs table exists (if using database queue)

### Queue System
- [ ] Queue worker is running: `php artisan queue:work`
- [ ] Queue connection is properly configured

## Testing Production Readiness

### 1. Run Health Check
```bash
php artisan tinker
```
```php
$service = new \App\Services\ProductionSafetyService();
$result = $service->checkProductionReadiness();
dd($result);
```

### 2. Test API Endpoint
```bash
curl -X POST https://yourdomain.com/api/catering/requests \
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

### 3. Monitor Response Time
- Response should be under 10 seconds
- If over 30 seconds, check queue configuration

## Common Production Issues

### Issue 1: Firebase Connection Failed
**Symptoms:** 500 error, "Firebase connection failed" in logs
**Solution:** 
1. Verify Firebase credentials file exists
2. Check file permissions
3. Verify environment variables

### Issue 2: Slow Response Times (>30 seconds)
**Symptoms:** API takes 56+ seconds to respond
**Solution:**
1. Check queue configuration (should not be 'sync')
2. Ensure queue worker is running
3. Check email configuration

### Issue 3: Email Sending Failed
**Symptoms:** Emails not being sent
**Solution:**
1. Verify SMTP credentials
2. Check firewall settings
3. Ensure queue worker is processing jobs

### Issue 4: File Permission Errors
**Symptoms:** "Permission denied" errors
**Solution:**
1. Fix storage directory permissions
2. Ensure web server owns the files
3. Check SELinux settings (if applicable)

## Monitoring Commands

### Check Queue Status
```bash
php artisan queue:work --verbose
```

### Monitor Logs
```bash
tail -f storage/logs/laravel.log
```

### Check Failed Jobs
```bash
php artisan queue:failed
```

### Retry Failed Jobs
```bash
php artisan queue:retry all
```

## Performance Optimization

### 1. Enable Route Caching
```bash
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

### 2. Optimize Autoloader
```bash
composer install --optimize-autoloader --no-dev
```

### 3. Database Optimization
- Add indexes for frequently queried fields
- Use database queue instead of sync queue

## Security Considerations

1. **Firebase Credentials**: Never commit credentials to version control
2. **Environment Variables**: Use secure methods to store sensitive data
3. **File Permissions**: Restrict access to credentials files
4. **Rate Limiting**: Configure appropriate rate limits for API endpoints

## Backup Strategy

1. **Database**: Regular backups of MySQL/PostgreSQL
2. **Firebase**: Regular exports of Firestore collections
3. **Files**: Backup of credentials and configuration files
4. **Logs**: Archive old log files

## Rollback Plan

1. Keep previous version deployment ready
2. Database migration rollback scripts
3. Environment variable backup
4. Configuration file backup

---

**⚠️ IMPORTANT:** Test thoroughly in staging environment before deploying to production. The async email system requires proper queue configuration to avoid performance issues.
