ARG PHP_VERSION=''

FROM php:${PHP_VERSION}

RUN /bin/sh -c 'set -e ; \
curl --silent --show-error --location https://phar.phpunit.de/phpunit-5.7.27.phar > /usr/local/bin/phpunit5; \
curl --silent --show-error --location https://phar.phpunit.de/phpunit-8.5.8.phar > /usr/local/bin/phpunit8; \
curl --silent --show-error --location https://phar.phpunit.de/phpunit-9.4.1.phar > /usr/local/bin/phpunit; \
chmod 700 /usr/local/bin/phpunit*; \
'

CMD test/run.sh ${PHP_VERSION}
