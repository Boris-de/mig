ARG PHP_VERSION=''

FROM php:${PHP_VERSION}

COPY tmp/phpunit* /usr/local/bin

CMD test/run.sh ${PHP_VERSION}
