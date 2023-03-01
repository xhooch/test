#!/bin/sh

cat /root/cronfile | crontab -

dockerize -wait tcp://${APP_DB_HOST}:${APP_DB_PORT} \
          -wait tcp://${APP_RABBIT_HOST}:${APP_RABBIT_PORT} \
          -wait tcp://${APP_REDIS_HOST}:${APP_REDIS_PORT} \
          -timeout ${WAITING_TIMEOUT}s

php /auction-app/yii migrate --interactive=0 --migrationPath=@yii/i18n/migrations/
php /auction-app/yii migrate --interactive=0
php /auction-app/yii rabbit-queues/init --interactive=0

supervisord
