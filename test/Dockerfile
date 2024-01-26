ARG PHP_VERSION=''

FROM docker.io/php:${PHP_VERSION}

# enable pcntl to use "enforceTimeLimit" in phpunit
RUN docker-php-ext-configure pcntl --enable-pcntl && docker-php-ext-install pcntl

COPY tmp/phpunit* /usr/local/bin/

CMD test/run.sh ${PHP_VERSION}
