#!/usr/bin/env bash
set -e

APP_DIR="${APP_DIR:-/var/www/services}"
if [[ -n "${SERVICE_SUBDIR}" ]]; then
  APP_DIR="${APP_DIR}/${SERVICE_SUBDIR}"
fi

echo "[entrypoint] Starting Laravel bootstrap for ${SERVICE_SUBDIR:-unknown} in ${APP_DIR}"

if [[ -f "${APP_DIR}/artisan" ]]; then
  cd "${APP_DIR}"

  # 1. Копируем .env при отсутствии
  if [[ ! -f ".env" ]] && [[ -f ".env.example" ]]; then
    echo "[entrypoint] .env not found → copying from .env.example"
    cp .env.example .env
  fi

  # 2. Генерируем APP_KEY, если пустой или placeholder
  APP_KEY_CURRENT="$(grep -E '^APP_KEY=' .env 2>/dev/null | cut -d'=' -f2- || true)"
  if [[ -z "${APP_KEY_CURRENT}" || "${APP_KEY_CURRENT}" == "base64:CHANGE_ME" ]]; then
    echo "[entrypoint] Generating new APP_KEY..."
    php artisan key:generate --force || true
  else
    echo "[entrypoint] Existing APP_KEY OK"
  fi

  # 3. Создаём storage-папки и выдаём права
  echo "[entrypoint] Fixing permissions..."
  mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache || true
  find storage bootstrap/cache -type d -exec chmod 775 {} \; || true
  find storage bootstrap/cache -type f -exec chmod 664 {} \; || true

  # 4. Чистим кэш
  echo "[entrypoint] Clearing caches..."
  php artisan config:clear || true
  php artisan cache:clear || true
  php artisan view:clear || true

  # 5. Пробуем миграции (если подключена база)
  if php artisan migrate:status > /dev/null 2>&1; then
    echo "[entrypoint] Running migrations..."
    php artisan migrate --force || true
  fi

  # 6. Storage symlink
  if [[ ! -L "public/storage" ]]; then
    php artisan storage:link >/dev/null 2>&1 || true
  fi

else
  echo "[entrypoint] No artisan found → skipping Laravel setup."
fi

echo "[entrypoint] Starting php-fpm..."
exec php-fpm
