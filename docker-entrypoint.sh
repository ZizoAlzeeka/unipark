#!/bin/bash
# UniPark docker entrypoint — fault tolerant.
#
# IMPORTANT: We intentionally DO NOT use `set -e` here.
# If migrations/seeders fail (e.g. DB not ready yet), we still want Apache
# to start so the container stays alive and the user can see the actual
# Laravel error page in the browser. Otherwise the container dies and
# Coolify shows a useless "no such object" error during uptime probing.

echo "============================================"
echo " UniPark - Smart Campus Parking - Starting"
echo "============================================"

# --------------------------------------------
# 1) Configure Apache to listen on port 80 (fixed)
#    The PORT env var from Coolify is intentionally IGNORED here —
#    we hardcode 80 so it always matches EXPOSE 80 in the Dockerfile
#    and the Port setting configured in Coolify UI.
# --------------------------------------------
RENDER_PORT=80
echo ">> Configuring Apache to listen on port $RENDER_PORT..."
sed -i "s/^Listen .*/Listen $RENDER_PORT/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:$RENDER_PORT>/" /etc/apache2/sites-available/000-default.conf

# --------------------------------------------
# 2) Show environment (for debugging, secrets masked)
# --------------------------------------------
echo ">> Environment check:"
echo "   - DB_HOST=${DB_HOST:-(not set)}"
echo "   - DB_PORT=${DB_PORT:-(not set)}"
echo "   - DB_DATABASE=${DB_DATABASE:-(not set)}"
echo "   - DB_USERNAME=${DB_USERNAME:-(not set)}"
[ -n "$DB_PASSWORD" ] && echo "   - DB_PASSWORD=***set***" || echo "   - DB_PASSWORD=(not set)"

# --------------------------------------------
# 3) Create .env from .env.example (if missing)
# --------------------------------------------
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        echo ">> Creating .env from .env.example..."
        cp /var/www/html/.env.example /var/www/html/.env
    else
        echo ">> WARNING: No .env or .env.example found, creating empty .env"
        touch /var/www/html/.env
    fi
fi

# --------------------------------------------
# 3.5) Clean any stale Laravel bootstrap cache (defensive).
# These files reference dev-only service providers (Collision, Ignition,
# Sail) that are NOT installed with --no-dev. If they leak in from the
# build context, every artisan command crashes with
# "Class NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider not found".
# `config:cache` will regenerate them at the end of this script.
# --------------------------------------------
rm -f /var/www/html/bootstrap/cache/services.php /var/www/html/bootstrap/cache/packages.php

# --------------------------------------------
# 4) Helper: set or replace env var in .env
# --------------------------------------------
set_env_var() {
    local key="$1"
    local value="$2"
    local envfile="/var/www/html/.env"
    # Escape forward slashes in value for sed
    local escaped_value
    escaped_value=$(printf '%s' "$value" | sed 's/[&/\]/\\&/g')
    if grep -q "^${key}=" "$envfile"; then
        sed -i "s|^${key}=.*|${key}=${escaped_value}|" "$envfile"
    else
        echo "${key}=${value}" >> "$envfile"
    fi
}

# --------------------------------------------
# 5) Sync environment variables from container → .env
# --------------------------------------------
echo ">> Syncing environment variables to .env file..."
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
[ -n "$SESSION_LIFETIME" ] && set_env_var "SESSION_LIFETIME" "$SESSION_LIFETIME"
[ -n "$CACHE_DRIVER" ] && set_env_var "CACHE_DRIVER" "$CACHE_DRIVER"
[ -n "$QUEUE_CONNECTION" ] && set_env_var "QUEUE_CONNECTION" "$QUEUE_CONNECTION"
[ -n "$FILESYSTEM_DISK" ] && set_env_var "FILESYSTEM_DISK" "$FILESYSTEM_DISK"
[ -n "$LOG_CHANNEL" ] && set_env_var "LOG_CHANNEL" "$LOG_CHANNEL"
[ -n "$BROADCAST_DRIVER" ] && set_env_var "BROADCAST_DRIVER" "$BROADCAST_DRIVER"
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
[ -n "$MAIL_HOST" ] && set_env_var "MAIL_HOST" "$MAIL_HOST"
[ -n "$MAIL_PORT" ] && set_env_var "MAIL_PORT" "$MAIL_PORT"
[ -n "$MAIL_USERNAME" ] && set_env_var "MAIL_USERNAME" "$MAIL_USERNAME"
[ -n "$MAIL_PASSWORD" ] && set_env_var "MAIL_PASSWORD" "$MAIL_PASSWORD"
[ -n "$MAIL_ENCRYPTION" ] && set_env_var "MAIL_ENCRYPTION" "$MAIL_ENCRYPTION"
[ -n "$MAIL_FROM_ADDRESS" ] && set_env_var "MAIL_FROM_ADDRESS" "$MAIL_FROM_ADDRESS"
[ -n "$MAIL_FROM_NAME" ] && set_env_var "MAIL_FROM_NAME" "$MAIL_FROM_NAME"
[ -n "$BROADCAST_DRIVER" ] && set_env_var "BROADCAST_DRIVER" "$BROADCAST_DRIVER"
[ -n "$SESSION_LIFETIME" ] && set_env_var "SESSION_LIFETIME" "$SESSION_LIFETIME"

# --------------------------------------------
# 6) Generate APP_KEY if missing
# --------------------------------------------
CURRENT_KEY=$(grep -E "^APP_KEY=base64:" /var/www/html/.env 2>/dev/null | head -1 | cut -d= -f2-)
if [ -z "$CURRENT_KEY" ]; then
    echo ">> Generating APP_KEY..."
    php /var/www/html/artisan key:generate --force || echo ">> WARNING: key:generate failed"
else
    echo ">> APP_KEY already set"
    export APP_KEY="$CURRENT_KEY"
fi

# --------------------------------------------
# 7) Wait for MySQL (soft check, doesn't block forever)
# --------------------------------------------
echo ">> Checking MySQL connection..."
MYSQL_READY=0
MAX_RETRIES=20
RETRY_COUNT=0
while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if php -r "new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; then
        echo ">> MySQL connection established!"
        MYSQL_READY=1
        break
    fi
    RETRY_COUNT=$((RETRY_COUNT + 1))
    echo ">> Waiting for MySQL... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 3
done

if [ $MYSQL_READY -eq 0 ]; then
    echo ">> WARNING: Could not connect to MySQL after $MAX_RETRIES attempts."
    echo ">> Apache will still start — Laravel will show its own error page."
fi

# --------------------------------------------
# 8) Run migrations (best-effort, don't crash container)
# --------------------------------------------
if [ $MYSQL_READY -eq 1 ]; then
    echo ">> Running database migrations..."
    php /var/www/html/artisan migrate --force || echo ">> WARNING: migrate failed, continuing anyway"

    echo ">> Checking if seeders should run..."
    USER_COUNT=$(php -r "
\$pdo = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
echo \$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
" 2>/dev/null || echo "0")
    if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
        echo ">> Running database seeders..."
        php /var/www/html/artisan db:seed --force || echo ">> WARNING: db:seed failed, continuing anyway"
    else
        echo ">> Database already seeded ($USER_COUNT users found), skipping"
    fi

    echo ">> Optimizing application for production..."
    php /var/www/html/artisan config:cache 2>/dev/null || echo ">> WARNING: config:cache failed"
    php /var/www/html/artisan route:cache 2>/dev/null || echo ">> WARNING: route:cache failed"
    php /var/www/html/artisan view:cache 2>/dev/null || echo ">> WARNING: view:cache failed"
else
    echo ">> Skipping migrations/seeders/cache (no DB connection)"
fi

# --------------------------------------------
# 9) Storage link + permissions
# --------------------------------------------
echo ">> Creating storage link..."
php /var/www/html/artisan storage:link --force 2>/dev/null || true

echo ">> Setting final permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "============================================"
echo " UniPark is ready!"
echo " Listening on port $RENDER_PORT"
echo " MySQL: $([ $MYSQL_READY -eq 1 ] && echo 'connected' || echo 'NOT connected')"
echo "============================================"

# --------------------------------------------
# 10) Start Apache (main process — must never exit)
# --------------------------------------------
exec "$@"
