#!/bin/sh
set -e

echo "[frontend] starting entrypoint"

if [ ! -f /srv/app/angular.json ]; then
  echo "[frontend] creating Angular project in /srv/app"
  if [ -f /srv/app/package.json ]; then
    echo "[frontend] existing package.json found — backing up and removing lock/node_modules to avoid merge conflicts"
    mv /srv/app/package.json /srv/app/package.json.bak || true
    rm -f /srv/app/package-lock.json || true
    rm -rf /srv/app/node_modules || true
  fi
  ng new wambo-frontend --directory . --defaults --skip-git --style=scss --routing=false --skip-install --skip-tests || true
  echo "[frontend] installing node dependencies"
  npm install --no-audit --no-fund
  npm install @angular/material @angular/cdk @angular/animations --no-audit --no-fund
fi

if [ -f /srv/app/angular.json ]; then
  if [ ! -d /srv/app/node_modules ] || [ ! -f /srv/app/node_modules/@angular-devkit/build-angular/package.json ]; then
    echo "[frontend] detected existing Angular workspace but node modules missing — installing"
    cd /srv/app
    npm install --no-audit --no-fund
    npm install @angular/material @angular/cdk @angular/animations --no-audit --no-fund || true
  fi
fi

cd /srv/app
echo "[frontend] starting ng serve on 0.0.0.0:4200 with proxy"
if [ -f /srv/app/proxy.conf.json ]; then
  ng serve --host 0.0.0.0 --proxy-config /srv/app/proxy.conf.json
else
  ng serve --host 0.0.0.0
fi
