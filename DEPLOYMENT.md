# 🚀 Deployment Guide

This guide covers deploying the Community Development System to production.

---

## 📋 Pre-Deployment Checklist

- [ ] All tests passing (`php artisan test`)
- [ ] Environment variables configured
- [ ] Database migrations ready
- [ ] SSL certificate obtained
- [ ] Backup strategy in place
- [ ] Monitoring tools configured

---

## 🌐 Server Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Memory**: 512MB RAM minimum (1GB+ recommended)
- **Storage**: 1GB minimum
- **SSL**: Required for production

### PHP Extensions Required
```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD or Imagick (for image processing)
```

---

## 🔧 Deployment Steps

### 1. Server Setup

#### For Ubuntu/Debian
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y

# Install MySQL
sudo apt install mysql-server -y
```

### 2. Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/yourusername/commdevsys.git
cd commdevsys
```

### 3. Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/commdevsys
sudo chmod -R 755 /var/www/commdevsys
sudo chmod -R 775 /var/www/commdevsys/storage
sudo chmod -R 775 /var/www/commdevsys/bootstrap/cache
```

### 4. Install Dependencies
```bash
# Install PHP dependencies (production)
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm ci

# Build production assets
npm run build
```

### 5. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit environment file
nano .env
```

**Production `.env` settings:**
```env
APP_NAME="Community Development System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=commdevsys_prod
DB_USERNAME=commdevsys_user
DB_PASSWORD=strong_password_here

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Database Setup
```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE commdevsys_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'commdevsys_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON commdevsys_prod.* TO 'commdevsys_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Run migrations
php artisan migrate --force

# Link storage
php artisan storage:link
```

### 7. Optimize Application
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 🌐 Web Server Configuration

### Nginx Configuration
Create `/etc/nginx/sites-available/commdevsys`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/commdevsys/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/commdevsys /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🔒 SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## 📊 Monitoring & Logging

### Setup Log Rotation
Create `/etc/logrotate.d/commdevsys`:

```
/var/www/commdevsys/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

---

## 🔄 Continuous Deployment

### Using Git Hooks
Create `/var/www/commdevsys/.git/hooks/post-receive`:

```bash
#!/bin/bash
cd /var/www/commdevsys
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.2-fpm
```

---

## 💾 Backup Strategy

### Database Backup Script
Create `/usr/local/bin/backup-commdevsys.sh`:

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/commdevsys"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u commdevsys_user -p'password' commdevsys_prod > $BACKUP_DIR/db_$DATE.sql

# Backup uploads
tar -czf $BACKUP_DIR/uploads_$DATE.tar.gz /var/www/commdevsys/storage/app/public

# Keep only last 30 days
find $BACKUP_DIR -type f -mtime +30 -delete
```

Add to crontab:
```bash
0 2 * * * /usr/local/bin/backup-commdevsys.sh
```

---

## 🚨 Troubleshooting

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/commdevsys
sudo chmod -R 775 storage bootstrap/cache
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

---

**Deployment Complete! 🎉**

