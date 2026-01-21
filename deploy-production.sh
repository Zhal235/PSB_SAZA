#!/bin/bash

###############################################################################
# PSB SAZA - SAFE PRODUCTION DEPLOYMENT SCRIPT
# Version: 1.0
# Date: 21 Januari 2026
# 
# Usage: 
#   ssh user@server "bash /home/user/deploy-psb-saza.sh"
# 
# This script will safely deploy the latest update to production
###############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_PATH="/var/www/psb-saza"  # GANTI SESUAI PATH PRODUCTION
BACKUP_PATH="/backups/psb-saza"
BRANCH="main"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[✓ OK]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[⚠ WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[✗ ERROR]${NC} $1"
}

check_prerequisites() {
    log_info "Checking prerequisites..."
    
    if [ ! -d "$APP_PATH" ]; then
        log_error "Application path not found: $APP_PATH"
        exit 1
    fi
    
    if ! command -v git &> /dev/null; then
        log_error "Git is not installed"
        exit 1
    fi
    
    if ! command -v php &> /dev/null; then
        log_error "PHP is not installed"
        exit 1
    fi
    
    if ! command -v composer &> /dev/null; then
        log_error "Composer is not installed"
        exit 1
    fi
    
    log_success "All prerequisites checked"
}

backup_database() {
    log_info "Creating database backup..."
    
    mkdir -p "$BACKUP_PATH"
    
    DB_FILE="$APP_PATH/database/database.sqlite"
    BACKUP_FILE="$BACKUP_PATH/database_${TIMESTAMP}.sqlite"
    
    if [ -f "$DB_FILE" ]; then
        cp "$DB_FILE" "$BACKUP_FILE"
        log_success "Database backed up to $BACKUP_FILE"
        
        # Keep only last 5 backups
        ls -t "$BACKUP_PATH"/database_*.sqlite | tail -n +6 | xargs -r rm
    else
        log_warning "Database file not found: $DB_FILE"
    fi
}

backup_app() {
    log_info "Creating application backup..."
    
    APP_BACKUP="$BACKUP_PATH/app_${TIMESTAMP}.tar.gz"
    tar -czf "$APP_BACKUP" \
        --exclude="node_modules" \
        --exclude="vendor" \
        --exclude="storage/logs" \
        --exclude=".git" \
        -C "$(dirname $APP_PATH)" "$(basename $APP_PATH)" 2>/dev/null
    
    log_success "Application backed up to $APP_BACKUP"
    
    # Keep only last 3 backups
    ls -t "$BACKUP_PATH"/app_*.tar.gz | tail -n +4 | xargs -r rm
}

maintenance_mode_on() {
    log_info "Enabling maintenance mode..."
    
    cd "$APP_PATH"
    php artisan down --render=errors::500 || true
    
    sleep 2
    log_success "Maintenance mode enabled"
}

maintenance_mode_off() {
    log_info "Disabling maintenance mode..."
    
    cd "$APP_PATH"
    php artisan up || true
    
    log_success "Maintenance mode disabled"
}

git_pull() {
    log_info "Pulling latest code from $BRANCH branch..."
    
    cd "$APP_PATH"
    
    # Stash any local changes
    git stash || true
    
    # Fetch latest
    git fetch origin
    
    # Check current branch
    CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
    
    if [ "$CURRENT_BRANCH" != "$BRANCH" ]; then
        log_warning "Current branch is $CURRENT_BRANCH, switching to $BRANCH"
        git checkout "$BRANCH" || true
    fi
    
    # Pull latest
    git pull origin "$BRANCH"
    
    log_success "Code pulled successfully"
}

install_dependencies() {
    log_info "Installing PHP dependencies..."
    
    cd "$APP_PATH"
    
    composer install --no-dev --optimize-autoloader
    
    log_success "PHP dependencies installed"
}

run_migrations() {
    log_info "Running database migrations..."
    
    cd "$APP_PATH"
    
    php artisan migrate --force
    
    log_success "Migrations completed"
}

cache_optimization() {
    log_info "Optimizing caches..."
    
    cd "$APP_PATH"
    
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    log_success "Caches optimized"
}

set_permissions() {
    log_info "Setting file permissions..."
    
    cd "$APP_PATH"
    
    # Set permissions for storage
    chmod -R 755 storage bootstrap/cache
    chmod -R 775 storage/logs storage/app
    
    # Set ownership (adjust user:group as needed)
    # Uncomment if running as root
    # chown -R www-data:www-data .
    
    log_success "File permissions set"
}

storage_link() {
    log_info "Creating storage symlink..."
    
    cd "$APP_PATH"
    
    # Remove old symlink if exists
    rm -f public/storage
    
    # Create new symlink
    php artisan storage:link
    
    log_success "Storage symlink created"
}

clear_temp_files() {
    log_info "Clearing temporary files..."
    
    cd "$APP_PATH"
    
    # Clear Laravel caches
    php artisan cache:clear
    php artisan config:clear
    
    # Clear node_modules cache if exists
    rm -rf node_modules/.cache 2>/dev/null || true
    
    log_success "Temporary files cleared"
}

health_check() {
    log_info "Running health checks..."
    
    cd "$APP_PATH"
    
    # Check database connection
    if php artisan tinker <<< "DB::connection()->getPdo(); echo 'DB OK';" &>/dev/null; then
        log_success "Database connection OK"
    else
        log_error "Database connection failed"
        return 1
    fi
    
    # Check app key
    if grep -q "^APP_KEY=" .env; then
        log_success "APP_KEY configured"
    else
        log_error "APP_KEY not configured"
        return 1
    fi
    
    # Check routes
    if php artisan route:list | grep -q "admin.dashboard"; then
        log_success "Routes OK"
    else
        log_error "Routes not found"
        return 1
    fi
    
    return 0
}

rollback() {
    log_warning "ROLLBACK INITIATED"
    
    if [ ! -z "$BACKUP_FILE" ] && [ -f "$BACKUP_FILE" ]; then
        log_info "Restoring database from backup..."
        cp "$BACKUP_FILE" "$APP_PATH/database/database.sqlite"
        log_success "Database restored"
    fi
    
    cd "$APP_PATH"
    php artisan up || true
    
    log_warning "Rollback completed. Check logs for details."
}

# Main deployment flow
main() {
    echo ""
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║     PSB SAZA - PRODUCTION DEPLOYMENT SCRIPT                ║"
    echo "║     Started: $(date '+%Y-%m-%d %H:%M:%S')                      ║"
    echo "╚════════════════════════════════════════════════════════════╝"
    echo ""
    
    # Pre-deployment
    check_prerequisites
    backup_database
    backup_app
    maintenance_mode_on
    
    # Deployment
    if ! git_pull; then
        log_error "Git pull failed!"
        maintenance_mode_off
        rollback
        exit 1
    fi
    
    if ! install_dependencies; then
        log_error "Dependency installation failed!"
        maintenance_mode_off
        rollback
        exit 1
    fi
    
    if ! run_migrations; then
        log_error "Migration failed!"
        maintenance_mode_off
        rollback
        exit 1
    fi
    
    set_permissions
    storage_link
    cache_optimization
    clear_temp_files
    
    # Post-deployment
    maintenance_mode_off
    
    if health_check; then
        log_success "Health checks passed!"
        log_success "Deployment completed successfully!"
    else
        log_error "Health checks failed!"
        log_warning "Please check the application manually"
        exit 1
    fi
    
    echo ""
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║     DEPLOYMENT COMPLETED SUCCESSFULLY                      ║"
    echo "║     Completed: $(date '+%Y-%m-%d %H:%M:%S')                      ║"
    echo "║                                                            ║"
    echo "║     Backups saved to: $BACKUP_PATH         ║"
    echo "╚════════════════════════════════════════════════════════════╝"
    echo ""
}

# Error handler
trap 'log_error "Script interrupted"; maintenance_mode_off; exit 1' INT TERM

# Run main
main "$@"
