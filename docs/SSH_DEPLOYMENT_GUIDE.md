# 🚀 SSH DEPLOYMENT COMMANDS - PSB SAZA

**Date:** 21 Januari 2026  
**Environment:** Linux Production Server

---

## 📋 SEBELUM DEPLOY

### 1. **Setup SSH Access**
```bash
# Dari local/Windows, test SSH connection terlebih dulu:
ssh user@your-production-server.com

# Jika belum bisa, setup SSH key:
# (lakukan di Windows PowerShell / Git Bash)
ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa_production

# Copy ke server:
ssh-copy-id -i ~/.ssh/id_rsa_production.pub user@your-production-server.com

# Test:
ssh -i ~/.ssh/id_rsa_production user@your-production-server.com "echo 'SSH OK'"
```

### 2. **Prepare Server (First Time Only)**
```bash
ssh user@your-production-server.com << 'EOF'

# Create directories
sudo mkdir -p /var/www/psb-saza
sudo mkdir -p /backups/psb-saza
sudo chown -R user:user /var/www/psb-saza /backups/psb-saza

# Clone repository
cd /var/www
git clone https://github.com/Zhal235/PSB_SAZA.git psb-saza
cd psb-saza

# Setup Laravel
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed

# Setup permissions
sudo chown -R www-data:www-data /var/www/psb-saza
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/app

echo "✓ Server setup complete"

EOF
```

---

## 🎯 DEPLOYMENT COMMANDS (SAFE UPDATE)

### **Option 1: Full Safe Deployment (RECOMMENDED)**

```bash
# Copy deployment script ke server (run dari local)
scp -i ~/.ssh/id_rsa_production deploy-production.sh user@your-production-server.com:/tmp/

# Execute deployment (run di server)
ssh -i ~/.ssh/id_rsa_production user@your-production-server.com << 'EOF'

# Make script executable
chmod +x /tmp/deploy-production.sh

# Run deployment
/tmp/deploy-production.sh

EOF
```

**What this does:**
- ✅ Automatic backups (database + app)
- ✅ Maintenance mode enabled
- ✅ Git pull latest code
- ✅ Install dependencies
- ✅ Run migrations
- ✅ Cache optimization
- ✅ Health checks
- ✅ Automatic rollback on error
- ✅ Maintenance mode disabled

---

### **Option 2: Quick Update (Faster, Less Safe)**

```bash
ssh -i ~/.ssh/id_rsa_production user@your-production-server.com << 'EOF'

cd /var/www/psb-saza

# Enable maintenance
php artisan down

# Update code
git fetch origin
git reset --hard origin/main

# Install & optimize
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# Disable maintenance
php artisan up

echo "✓ Quick update complete"

EOF
```

---

### **Option 3: Manual Step-by-Step (Full Control)**

```bash
# SSH into server
ssh -i ~/.ssh/id_rsa_production user@your-production-server.com

# Then run commands one by one:

# 1. Go to app directory
cd /var/www/psb-saza

# 2. Backup
mkdir -p /backups/psb-saza
cp database/database.sqlite /backups/psb-saza/backup_$(date +%s).sqlite

# 3. Maintenance mode
php artisan down

# 4. Pull code
git fetch origin
git checkout main
git pull origin main

# 5. Dependencies
composer install --no-dev --optimize-autoloader

# 6. Database
php artisan migrate --force

# 7. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/app

# 9. Storage link
php artisan storage:link

# 10. Health check
php artisan tinker
# Lalu test:
# DB::connection()->getPdo();
# Route::has('admin.dashboard');

# 11. Maintenance off
php artisan up

# 12. Check logs
tail -f storage/logs/laravel.log
```

---

## 🔄 ROLLBACK (Jika Ada Error)

```bash
ssh -i ~/.ssh/id_rsa_production user@your-production-server.com << 'EOF'

cd /var/www/psb-saza

# 1. Restore database backup
cp /backups/psb-saza/database_TIMESTAMP.sqlite database/database.sqlite

# 2. Restore app (jika ada git history)
git reset --hard HEAD~1
# atau
git revert HEAD

# 3. Re-install dependencies
composer install --no-dev

# 4. Disable maintenance
php artisan up

# 5. Check status
php artisan migrate:status
php artisan route:list | head -20

echo "✓ Rollback complete"

EOF
```

---

## 📊 MONITORING & VERIFICATION

### **Check Deployment Status**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-production-server.com << 'EOF'

cd /var/www/psb-saza

# Check Laravel status
php artisan tinker
# Inside: Route::has('admin.dashboard')

# Check latest commit
git log --oneline -3

# Check migrations
php artisan migrate:status

# Check routes
php artisan route:list | grep admin

# Check logs
tail -50 storage/logs/laravel.log

# Check permissions
ls -la storage/logs

EOF
```

### **Real-time Log Monitoring**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-production-server.com \
    "tail -f /var/www/psb-saza/storage/logs/laravel.log"
```

---

## ⚙️ CONFIGURATION TEMPLATE

**Sesuaikan di deploy script atau environment:**

```bash
# deploy-production.sh - edit ini:
APP_PATH="/var/www/psb-saza"  # ← Sesuaikan path production
BACKUP_PATH="/backups/psb-saza"
BRANCH="main"

# .env di server production:
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite  # atau mysql/pgsql
CACHE_DRIVER=redis  # recommended
SESSION_DRIVER=redis  # recommended
```

---

## 🛡️ SAFETY CHECKLIST

Sebelum deploy:
- [ ] Backup database sudah dibuat
- [ ] SSH key sudah setup
- [ ] Production server siap (tidak ada ongoing maintenance)
- [ ] Latest code sudah di-commit dan di-push ke main
- [ ] Test di staging server dulu
- [ ] Maintenance mode ready
- [ ] Rollback plan ready

---

## 📱 UNTUK WINDOWS USERS

### **Menggunakan Git Bash atau WSL:**
```bash
# Di Git Bash:
ssh -i "C:\Users\YourName\.ssh\id_rsa_production" user@your-server.com

# Atau buat alias di .bash_profile:
alias deploy-psb='ssh -i ~/.ssh/id_rsa_production user@your-server.com "bash /tmp/deploy-production.sh"'

# Lalu cukup:
deploy-psb
```

### **Menggunakan PowerShell (Windows 11+):**
```powershell
# Test SSH
ssh -i "$env:USERPROFILE\.ssh\id_rsa_production" user@your-server.com

# Or create a batch script: deploy.bat
@echo off
ssh -i "%USERPROFILE%\.ssh\id_rsa_production" user@your-server.com "bash /tmp/deploy-production.sh"
pause
```

---

## 🚨 TROUBLESHOOTING

| Problem | Solution |
|---------|----------|
| SSH connection refused | Check firewall, verify server IP, test SSH key |
| Permission denied | Verify SSH key permissions (chmod 600) |
| Git clone fails | Check credentials, verify repository access |
| Composer out of memory | `php -d memory_limit=-1 /usr/local/bin/composer install` |
| Migration fails | Check database connection, verify migration files |
| App still in maintenance | `php artisan up` manually |
| Logs show errors | `tail -100 storage/logs/laravel.log` |

---

## 📞 EMERGENCY CONTACTS

Jika ada error:
1. Check logs: `tail -f storage/logs/laravel.log`
2. SSH ke server manual
3. Rollback ke commit sebelumnya
4. Contact DevOps team

---

**Last Updated:** 21 Januari 2026  
**Safe Deployment Version:** 1.0