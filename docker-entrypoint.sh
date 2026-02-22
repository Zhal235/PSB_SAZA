#!/bin/bash
set -e

cd /var/www

echo "=== PSB SAZA Docker Entrypoint ==="

# Generate .env from container environment variables
cat > /var/www/.env <<EOF
APP_NAME="${APP_NAME:-PSB SAZA}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stderr
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL="${LOG_LEVEL:-warning}"

DB_CONNECTION=mysql
DB_HOST="${DB_HOST:-psb-db}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-psb_saza}"
DB_USERNAME="${DB_USERNAME:-psb_user}"
DB_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER="${MAIL_MAILER:-log}"
MAIL_HOST="${MAIL_HOST:-127.0.0.1}"
MAIL_PORT="${MAIL_PORT:-2525}"
MAIL_USERNAME="${MAIL_USERNAME}"
MAIL_PASSWORD="${MAIL_PASSWORD}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-noreply@psb-saza.id}"
MAIL_FROM_NAME="\${APP_NAME}"
EOF

# Generate APP_KEY if not provided
if [ -z "$APP_KEY" ]; then
    echo "[INFO] APP_KEY not set, generating new key..."
    NEW_KEY=$(php artisan key:generate --show --no-interaction)
    sed -i "s|^APP_KEY=.*|APP_KEY=${NEW_KEY}|" /var/www/.env
    export APP_KEY="$NEW_KEY"
    echo "[WARN] Generated APP_KEY: ${NEW_KEY}"
    echo "[WARN] Save this key in Dokploy environment variables: APP_KEY=${NEW_KEY}"
else
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" /var/www/.env
fi

# Wait for MySQL to be ready
echo "[INFO] Waiting for MySQL at ${DB_HOST:-psb-db}:${DB_PORT:-3306}..."
max_tries=30
tries=0
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST:-psb-db};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-psb_saza}',
            '${DB_USERNAME:-psb_user}',
            '${DB_PASSWORD:-}'
        );
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    tries=$((tries + 1))
    if [ "$tries" -ge "$max_tries" ]; then
        echo "[WARN] MySQL not ready after ${max_tries} attempts, continuing anyway..."
        break
    fi
    echo "[INFO] Waiting for MySQL... attempt ${tries}/${max_tries}"
    sleep 3
done

echo "[INFO] Running database migrations..."
php artisan migrate --force --no-interaction

echo "[INFO] Running database seeders (if first run)..."
php artisan db:seed --force --no-interaction 2>/dev/null || true

echo "[INFO] Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "[INFO] Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[INFO] Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "=== Startup complete, launching services ==="
exec "$@"
