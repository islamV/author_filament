#!/usr/bin/env sh
set -e
umask 002

cd /var/www/html

# Ensure runtime cache paths exist (handles fresh volumes)
echo "[entrypoint] Ensuring storage and cache paths exist..."
mkdir -p storage/framework/cache \
         storage/framework/data \
         storage/framework/sessions \
         storage/framework/testing \
         storage/framework/views \
         storage/logs \
         bootstrap/cache
# Ensure log file exists without truncating if already present
[ -f storage/logs/laravel.log ] || touch storage/logs/laravel.log
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rw storage bootstrap/cache || true

# Ensure storage symlink exists (idempotent)
if [ -f artisan ]; then
  php artisan storage:link || true
  php artisan migrate || true
fi

# Optional runtime asset build (useful for Filament tweaks)
if [ "${VITE_BUILD_ON_STARTUP}" = "true" ]; then
  echo "[entrypoint] Building frontend assets at runtime..."
  if [ -f package-lock.json ]; then
    npm ci --no-audit --no-fund || npm install --no-audit --no-fund
  else
    npm install --no-audit --no-fund
  fi
  npm run build || echo "[entrypoint] Vite build failed; serving existing assets if any."
fi

# If APP_KEY is provided, warm caches; keep idempotent
if [ -f artisan ] && [ -n "${APP_KEY}" ]; then
  echo "[entrypoint] Clearing stale Laravel caches..."
  php artisan optimize:clear || true

  if command -v npm >/dev/null 2>&1 && [ -f package.json ]; then
    echo "[entrypoint] Rebuilding frontend assets..."
    if [ -f package-lock.json ]; then
      npm ci --no-audit --no-fund || npm install --no-audit --no-fund
    else
      npm install --no-audit --no-fund
    fi
    npm run build || echo "[entrypoint] npm run build failed; serving existing assets."
  fi

  echo "[entrypoint] Publishing Filament assets..."
  php artisan filament:assets || true

  echo "[entrypoint] Warming Laravel caches..."
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
  php artisan optimize || true
else
  echo "[entrypoint] Skipping artisan cache warmup (no APP_KEY or artisan missing)."
  if [ -z "${APP_KEY}" ]; then
    echo "[entrypoint] Hint: Set APP_KEY in Dokploy env vars (dont rely on .env)."
  fi
fi

echo "[entrypoint] Starting supervisord (nginx + php-fpm)"
exec /usr/bin/supervisord -c /etc/supervisord.conf
