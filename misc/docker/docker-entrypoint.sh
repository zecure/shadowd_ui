#!/bin/bash
set -e

SHADOWD_UI_CONFIG="/var/shadowd_ui/app/config/parameters.yml"

if [ -z "$SHADOWD_DB_DRIVER" ]; then
    SHADOWD_DB_DRIVER="pdo_pgsql"
fi
if [ -z "$SHADOWD_DB_HOST" ]; then
    SHADOWD_DB_HOST="127.0.0.1"
fi
if [ -z "$SHADOWD_DB_PORT" ]; then
    SHADOWD_DB_PORT="~"
fi
if [ -z "$SHADOWD_DB_NAME" ]; then
    SHADOWD_DB_NAME="shadowd"
fi
if [ -z "$SHADOWD_DB_USER" ]; then
    SHADOWD_DB_USER="shadowd"
fi
if [ -z "$SHADOWD_DB_PASSWORD" ]; then
    SHADOWD_DB_PASSWORD="~"
fi
if [ -z "$SHADOWD_DEMO" ]; then
    SHADOWD_DEMO="0"
fi

sed -i "s/PLACEHOLDER_DB_DRIVER/$SHADOWD_DB_DRIVER/g" "$SHADOWD_UI_CONFIG"
sed -i "s/PLACEHOLDER_DB_HOST/$SHADOWD_DB_HOST/g" "$SHADOWD_UI_CONFIG"
sed -i "s/PLACEHOLDER_DB_PORT/$SHADOWD_DB_PORT/g" "$SHADOWD_UI_CONFIG"
sed -i "s/PLACEHOLDER_DB_NAME/$SHADOWD_DB_NAME/g" "$SHADOWD_UI_CONFIG"
sed -i "s/PLACEHOLDER_DB_USER/$SHADOWD_DB_USER/g" "$SHADOWD_UI_CONFIG"
sed -i "s/PLACEHOLDER_DB_PASSWORD/$SHADOWD_DB_PASSWORD/g" "$SHADOWD_UI_CONFIG"
sed -i "s/PLACEHOLDER_DEMO/$SHADOWD_DEMO/g" "$SHADOWD_UI_CONFIG"
sed -i "s/PLACEHOLDER_SECRET_TOKEN/$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | head -c 32)/g" "$SHADOWD_UI_CONFIG"

sleep 15
su -s /bin/sh -c 'php app/console cache:warmup --quiet' www-data
php app/console assets:install web --symlink --quiet

echo "Starting command $@"
exec "$@"
