# 🚀 QUICK SSH DEPLOYMENT REFERENCE

## 🎯 FASTEST WAY TO DEPLOY

### **Step 1: Copy Script to Server (dari local)**
```bash
scp -i ~/.ssh/id_rsa_production deploy-production.sh user@your-server.com:/tmp/
```

### **Step 2: Run Deployment (di server)**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-server.com "chmod +x /tmp/deploy-production.sh && /tmp/deploy-production.sh"
```

**SELESAI!** ✅ Automatic deployment dengan:
- ✅ Backups
- ✅ Maintenance mode
- ✅ Code update
- ✅ Dependencies
- ✅ Migrations
- ✅ Caching
- ✅ Health checks
- ✅ Auto-rollback if error

---

## 📋 COPY-PASTE TEMPLATES

### **Template 1: For FIRST TIME SETUP**
```bash
# Edit dulu: ganti YOUR_DOMAIN, user, password
ssh user@YOUR_DOMAIN << 'EOF'
cd ~
sudo apt-get update
sudo apt-get install -y php composer git
cd /var/www
sudo git clone https://github.com/Zhal235/PSB_SAZA.git psb-saza
cd psb-saza
cp .env.example .env
php artisan key:generate
php artisan migrate
sudo chown -R www-data:www-data .
chmod -R 775 storage
echo "✓ Setup complete"
EOF
```

### **Template 2: For REGULAR UPDATES**
```bash
ssh user@YOUR_DOMAIN << 'EOF'
cd /var/www/psb-saza
php artisan down
git fetch origin && git reset --hard origin/main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan up
echo "✓ Update complete"
EOF
```

### **Template 3: For EMERGENCY ROLLBACK**
```bash
ssh user@YOUR_DOMAIN << 'EOF'
cd /var/www/psb-saza
git reset --hard HEAD~1
php artisan up
echo "✓ Rollback done"
EOF
```

---

## ⚡ ONE-LINER COMMANDS

### **Quick Status Check**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-server.com "cd /var/www/psb-saza && git log --oneline -1 && php artisan route:list | grep admin.dashboard | head -1"
```

### **View Live Logs**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-server.com "tail -f /var/www/psb-saza/storage/logs/laravel.log"
```

### **Check Database**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-server.com "cd /var/www/psb-saza && php artisan tinker <<< 'DB::connection()->getPdo(); echo \"DB OK\";'"
```

### **Quick Fix - Clear Cache**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-server.com "cd /var/www/psb-saza && php artisan cache:clear && php artisan config:clear"
```

### **Quick Fix - Fix Permissions**
```bash
ssh -i ~/.ssh/id_rsa_production user@your-server.com "cd /var/www/psb-saza && chmod -R 755 storage bootstrap/cache && chmod -R 775 storage/logs"
```

---

## 🔧 CONFIGURATION BEFORE DEPLOY

**Edit deploy-production.sh line 22-24:**
```bash
APP_PATH="/var/www/psb-saza"           # ← Your app path
BACKUP_PATH="/backups/psb-saza"        # ← Your backup path
BRANCH="main"                          # ← Branch to deploy
```

**Or set via SSH (before first run):**
```bash
ssh user@your-server.com << 'EOF'
mkdir -p /var/www/psb-saza
mkdir -p /backups/psb-saza
export APP_PATH="/var/www/psb-saza"
export BACKUP_PATH="/backups/psb-saza"
echo "✓ Paths configured"
EOF
```

---

## 🛡️ SAFETY TIPS

1. **Always backup first:**
   ```bash
   ssh user@your-server.com "cp /var/www/psb-saza/database/database.sqlite /backups/psb-saza/backup_$(date +%s).sqlite"
   ```

2. **Test in staging first:**
   ```bash
   # Deploy to staging before production
   # Verify everything works
   # Then deploy to production
   ```

3. **Check logs after deploy:**
   ```bash
   ssh user@your-server.com "tail -50 /var/www/psb-saza/storage/logs/laravel.log"
   ```

4. **Keep backups:**
   ```bash
   ssh user@your-server.com "ls -lh /backups/psb-saza/"
   ```

---

## 🚨 IF SOMETHING GOES WRONG

1. **Enable debug mode (temporary):**
   ```bash
   ssh user@your-server.com "cd /var/www/psb-saza && sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env"
   ```

2. **Check specific error:**
   ```bash
   ssh user@your-server.com "cd /var/www/psb-saza && tail -100 storage/logs/laravel.log | grep -A5 -B5 ERROR"
   ```

3. **Force disable maintenance:**
   ```bash
   ssh user@your-server.com "cd /var/www/psb-saza && php artisan up --force"
   ```

4. **Restore from backup:**
   ```bash
   ssh user@your-server.com "cp /backups/psb-saza/database_TIMESTAMP.sqlite /var/www/psb-saza/database/database.sqlite"
   ```

---

## 📊 POST-DEPLOYMENT VERIFICATION

```bash
ssh user@your-server.com << 'EOF'
cd /var/www/psb-saza

# Check latest commit
echo "=== Latest Commit ==="
git log --oneline -1

# Check if routes loaded
echo "=== Routes Check ==="
php artisan route:list | grep admin.dashboard

# Check database
echo "=== Database Check ==="
php artisan migrate:status | head -5

# Check file permissions
echo "=== Permissions Check ==="
ls -ld storage/logs

# Check Laravel status
echo "=== App Status ==="
php artisan tinker <<< 'Cache::forget("laravel_cache"); echo "Cache cleared"; exit();' 2>/dev/null || echo "Cache OK"

echo "✓ All checks complete"
EOF
```

---

## 📞 NEED HELP?

- **Read full guide:** `docs/SSH_DEPLOYMENT_GUIDE.md`
- **Check deployment checklist:** `docs/PRODUCTION_DEPLOYMENT_CHECKLIST.md`
- **View logs:** `tail -f storage/logs/laravel.log`
- **Rollback:** Use git to go back one commit

---

**Version:** 1.0  
**Last Updated:** 21 Januari 2026  
**Status:** Ready for Production ✅