# Production Environment Variables for JippyMart

Based on your Firebase credentials, here are the environment variables you should set:

## Firebase Configuration
```bash
FIREBASE_PROJECT_ID=jippymart-27c08
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCibAvFMfB1GayK\nBfl+uzzXT7SEqGEFIPncBp1JHsSgXjwap4C+U69wN5dLMr24q7KTm2TKPKKaYLWr\ny3FkHZ1hR2bbOU2ZjZ2xfLPH8th+4x7XjlI7Xi7wu4xnKUe50JC7erEQF0p8lXPe\n28DIDSST9LUavOKeZ+RCe9HstQyLzvsmzuGa1kNn5aKL+DtVL048Hz3/vBjN0k9M\nwuHos3rr9k3oSGunXxYzuMJX9peoZHh4p75z4WwKBONYJwvV8FZpRWacMnG4UpVy\nipKRSK1rx+L+mrbaGJSIobFPXbSRCRskOsr7tMoIylViu1qC5QVtQdsN8egY4/Tu\nmjAaX79bAgMBAAECggEAAdraB49hjdhbcBSIf49ZLvi/Y3Y6lzKgUohH6qwUnXIE\nSF76OzI16V6ctmDqacF9/7UaKK397WVpyiqxgpiFw5hrYLyokqhbX1f5roYAWg8P\nuxvN30bOvt9JAIgoJ8OKAAvk4OFGxPAhx/Y29IOx6QXEL0SnLlYog2gU0TZVcB+7\n5TWKCXRtZZu5zPB9eMoNkkDtEV9CxYGQa7QI1A+kPiruBCIyW15kv9yIwvmy45Ip\nWIMYeEBTTBbg6h9Og36Dk1yes5GP7octpruifM+JFL28x1hcZPJCCtD+vUE2khb2\nkLUYkiZNcfdDfjsmV49z3ru149EQZemllwC39JGOgQKBgQDaVkNPs2SGvf373JJ/\nTA9Xe7F+WR1MrXWlwuR7ArJ83ek/nA/ZYoCIicXzjw8f4JmxXhcnzeN67fUdgRr9\nJkpgtI+L9NMWDNJ7zTQTvHMY56k89EaL3l2y5xL+bK/YYHuxDk9MqJKLOJ18rdpx\nvJUEIK1zLr9K71roehlos9VdbQKBgQC+cJYqRGWqB3f3osGkL+Z0D/4YQIDM7Z/0\nqJKqqo5EH/fcwYHAtRtSD6qJocD6PAgIKmA28qRAxxlPh+vqda58qk5iMLUdnYPW\n3SUguzYSv8+v2vcRwdDQY7X+4rIGO7u1S7LPJyEq9Xs+zuHHAvZbTowitoKni6Xz\nTUCt4Iz65wKBgGmP539zVpkm5tOM82+EkK5VaZ2S7G2UOBtqvRkUVsO0bv+ILsmF\nrGtXYVO+ySs6ZlSxDzT5gJCA2E/piej3SGK1Keoa9qvkiye8MP+Rz3wtFVAZMrqR\nSnAi9LX6SpKtI2CZcJG5txCE0O/lBoaTDOEwA2x7H5XVX/iigAJ1YCWJAoGAdk1q\ne4dRwHYzSFqLAHvhJWhp6JbpbGSRYWL2+Z5KEnfpefGbsSLQfgHNZwJc5xYzxx7L\n9lZ1QuhsCMPfAh9o7Xi+iLDJUkRviiKG1fZ2eN8/HXdg1F8aYNoQTO52uPiSeuUz\nhww/xngIv9O03fQxJjd9RnMqZvqRdLsg2uMLsTECgYAl5ieiOIXL/6dWV9gg8sOm\nVvPn9qa+kNoQEddpf2nGLQ07qQOv/xg/I60Lyw9ut1s8uJht+opCyFxkDPyDcNiD\nf3kwvdLgpSheTHhfDPvHs6SM9yRkUa5pZkgRGekX8rwDl3lCoMb01ImzcQwjs3Dh\n7g3xiWQvD+stywr5VxBXug==\n-----END PRIVATE KEY-----\n"
FIREBASE_CLIENT_EMAIL=592427852800-compute@developer.gserviceaccount.com
FIREBASE_PRIVATE_KEY_ID=7191b6fdcd7434c2dfc37c891f039aadd07650fd
FIREBASE_CLIENT_ID=113289244906104996592
FIREBASE_CLIENT_X509_CERT_URL=https://www.googleapis.com/robot/v1/metadata/x509/592427852800-compute%40developer.gserviceaccount.com
```

## Email Configuration (You need to set these)
```bash
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-email@jippymart.in
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jippymart.in
MAIL_FROM_NAME="JippyMart Catering"
```

## Queue Configuration (Important for performance)
```bash
QUEUE_CONNECTION=database
# OR
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

## Application Configuration
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://jippymart.in
```

## Catering Specific Configuration
```bash
CATERING_ADMIN_EMAIL=jerry@jippymart.in
CATERING_FROM_EMAIL=noreply@jippymart.in
CATERING_FROM_NAME="JippyMart Catering"
CATERING_RATE_LIMIT_PUBLIC=5
CATERING_RATE_LIMIT_ADMIN=60
CATERING_SPAM_THRESHOLD=3
```

## Database Configuration
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

## Security Configuration
```bash
APP_KEY=your-32-character-secret-key
SANCTUM_STATEFUL_DOMAINS=jippymart.in,www.jippymart.in
SESSION_DOMAIN=.jippymart.in
```

## Important Notes:

1. **Replace the Firebase credentials** with new ones after regenerating
2. **Set proper email SMTP settings** for your hosting provider
3. **Configure queue system** to avoid slow response times
4. **Set APP_KEY** to a secure 32-character string
5. **Use HTTPS** for all URLs in production

## Testing Your Configuration

After setting these variables, test with:
```bash
curl https://jippymart.in/api/health
```

This will tell you if everything is configured correctly.
