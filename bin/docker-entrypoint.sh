#!/bin/sh
set -e

echo "[backend] starting entrypoint"

if [ ! -f /srv/app/composer.json ]; then
  echo "[backend] no composer.json found — creating Symfony 5 project in /srv/app"
  if [ -n "${SKIP_COMPOSER_INSTALL}" ]; then
    echo "[backend] SKIP_COMPOSER_INSTALL is set — creating project without installing dependencies"
    composer create-project symfony/skeleton:"5.4.*" /tmp/app --no-interaction --no-install
  else
    composer create-project symfony/skeleton:"5.4.*" /tmp/app --no-interaction
  fi
  echo "[backend] copying generated project into /srv/app (preserving existing scaffolding files)"
  mkdir -p /srv/app
  tar -C /tmp/app --exclude Dockerfile --exclude docker-entrypoint.sh -cf - . | tar -C /srv/app -xpf -
  cd /srv/app
  if [ -n "${SKIP_COMPOSER_INSTALL}" ]; then
    echo "[backend] SKIP_COMPOSER_INSTALL set — skipping Composer install and require steps.\nTo finish later run inside backend: 'composer install' and then 'composer require symfony/orm-pack doctrine/doctrine-bundle symfony/maker-bundle'"
  else
    echo "[backend] running 'composer install' without scripts (will not run post-install scripts that require DB)"
    composer install --no-interaction --no-scripts || true
    echo "[backend] adding recommended packages without running scripts"
    composer require symfony/orm-pack doctrine/doctrine-bundle symfony/maker-bundle --no-interaction --no-scripts || true
    echo "[backend] Composer packages installed without running scripts.\nAttempting to run post-install scripts when the database is ready (will retry for up to 5 minutes)..."
    tries=0
    max_tries=60
    until composer run-script post-install-cmd; do
      tries=$((tries+1))
      if [ "$tries" -ge "$max_tries" ]; then
        echo "[backend] timed out after $((max_tries*5))s trying to run post-install scripts.\nYou can run them later inside the container:\n  composer run-script post-install-cmd\n  composer run-script post-update-cmd"
        break
      fi
      echo "[backend] post-install scripts failed; waiting 5s and retrying ($tries/$max_tries)"
      sleep 5
    done
  fi
fi

if [ -n "${DATABASE_URL}" ] && [ -f /srv/app/.env ]; then
  echo "[backend] setting DATABASE_URL"
  sed -i "s|DATABASE_URL=.*|DATABASE_URL='${DATABASE_URL}'|g" /srv/app/.env || true
fi

cd /srv/app
echo "[backend] starting PHP built-in server on 0.0.0.0:8000"
php -S 0.0.0.0:8000 -t public
