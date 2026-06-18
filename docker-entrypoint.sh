#!/bin/bash
set -e

echo "============================================"
echo " UniPark - Smart Campus Parking - Starting"
echo "============================================"

# Configure Apache to listen on the PORT provided (Coolify/Render/VPS)
RENDER_PORT="${PORT:-80}"
echo ">> Configuring Apache to listen on port $RENDER_PORT..."
sed -i "s/Listen 80/Listen $RENDER_PORT/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$RENDER_PORT>/" /etc/apache2/sites-available/000-default.conf

# Wait for MySQL to be ready using a simple PDO check
echo ">> Checking MySQL connection..."
MAX_RETRIES=30
RETRY_COUNT=0

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if php -r "new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; then
        echo ">> MySQL connection established!"
        break
    fi

    RETRY_COUNT=$((RETRY_COUNT + 1))
    echo ">> Waiting for MySQL... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 3
done

if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
    echo ">> WARNING: Could not verify MySQL connection, continuing anyway..."
fi

# Create .env file from .env.example if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo ">> Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# IMPORTANT: Sync environment variables into .env file.
# Docker/Compose passes env vars to the container, but Laravel reads from .env file.
echo ">> Syncing environment variables to .env file..."

# Helper function: replace or add a key in .env
set_env_var() {
    local key="$1"
    local value="$2"
    local envfile="/var/www/html/.env"
    if grep -q "^${key}=" "$envfile"; then
        sed -i "s|^${key}=.*|${key}=${value}|" "$envfile"
    else
        echo "${key}=${value}" >> "$envfile"
    fi
}

# Sync critical Laravel env vars from container environment
[ -n "$APP_NAME" ] && set_env_var "APP_NAME" "$APP_NAME"
[ -n "$APP_ENV" ] && set_env_var "APP_ENV" "$APP_ENV"
[ -n "$APP_DEBUG" ] && set_env_var "APP_DEBUG" "$APP_DEBUG"
[ -n "$APP_URL" ] && set_env_var "APP_URL" "$APP_URL"
[ -n "$DB_CONNECTION" ] && set_env_var "DB_CONNECTION" "$DB_CONNECTION"
[ -n "$DB_HOST" ] && set_env_var "DB_HOST" "$DB_HOST"
[ -n "$DB_PORT" ] && set_env_var "DB_PORT" "$DB_PORT"
[ -n "$DB_DATABASE" ] && set_env_var "DB_DATABASE" "$DB_DATABASE"
[ -n "$DB_USERNAME" ] && set_env_var "DB_USERNAME" "$DB_USERNAME"
[ -n "$DB_PASSWORD" ] && set_env_var "DB_PASSWORD" "$DB_PASSWORD"
[ -n "$SESSION_DRIVER" ] && set_env_var "SESSION_DRIVER" "$SESSION_DRIVER"
[ -n "$CACHE_DRIVER" ] && set_env_var "CACHE_DRIVER" "$CACHE_DRIVER"
[ -n "$QUEUE_CONNECTION" ] && set_env_var "QUEUE_CONNECTION" "$QUEUE_CONNECTION"
[ -n "$FILESYSTEM_DISK" ] && set_env_var "FILESYSTEM_DISK" "$FILESYSTEM_DISK"
[ -n "$LOG_CHANNEL" ] && set_env_var "LOG_CHANNEL" "$LOG_CHANNEL"
[ -n "$MAIL_MAILER" ] && set_env_var "MAIL_MAILER" "$MAIL_MAILER"
[ -n "$MAIL_HOST" ] && set_env_var "MAIL_HOST" "$MAIL_HOST"
[ -n "$MAIL_PORT" ] && set_env_var "MAIL_PORT" "$MAIL_PORT"
[ -n "$MAIL_USERNAME" ] && set_env_var "MAIL_USERNAME" "$MAIL_USERNAME"
[ -n "$MAIL_PASSWORD" ] && set_env_var "MAIL_PASSWORD" "$MAIL_PASSWORD"
[ -n "$MAIL_ENCRYPTION" ] && set_env_var "MAIL_ENCRYPTION" "$MAIL_ENCRYPTION"
[ -n "$MAIL_FROM_ADDRESS" ] && set_env_var "MAIL_FROM_ADDRESS" "$MAIL_FROM_ADDRESS"
[ -n "$MAIL_FROM_NAME" ] && set_env_var "MAIL_FROM_NAME" "$MAIL_FROM_NAME"
[ -n "$GOOGLE_MAPS_API_KEY" ] && set_env_var "GOOGLE_MAPS_API_KEY" "$GOOGLE_MAPS_API_KEY"
[ -n "$UNIVERSITY_EMAIL_DOMAIN" ] && set_env_var "UNIVERSITY_EMAIL_DOMAIN" "$UNIVERSITY_EMAIL_DOMAIN"

# Generate application key ONLY if .env file does not already have a valid APP_KEY
CURRENT_KEY=$(grep -E "^APP_KEY=base64:" /var/www/html/.env 2>/dev/null | head -1 | cut -d= -f2-)
if [ -z "$CURRENT_KEY" ]; then
    echo ">> Generating application key..."
    php /var/www/html/artisan key:generate --force
else
    echo ">> APP_KEY already set in .env, skipping generation"
    export APP_KEY="$CURRENT_KEY"
fi

# Run database migrations
echo ">> Running database migrations..."
php /var/www/html/artisan migrate --force

# Check if we should run seeders (only if users table is empty)
USER_COUNT=$(php -r "
\$pdo = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
echo \$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo ">> Running database seeders..."
    php /var/www/html/artisan db:seed --force
else
    echo ">> Database already seeded ($USER_COUNT users found), skipping seeders..."
fi

# Clear and cache config/routes for production
echo ">> Optimizing application for production..."
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# Ensure storage link exists
echo ">> Creating storage link..."
php /var/www/html/artisan storage:link --force 2>/dev/null || true

# Fix permissions one more time
echo ">> Setting final permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "============================================"
echo " UniPark is ready!"
echo " Listening on port $RENDER_PORT"
echo "============================================"

# Execute the main process (Apache)
exec "$@"
