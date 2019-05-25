ARG PHP_VERSION=''

FROM php:${PHP_VERSION}

RUN /bin/sh -c 'set -e ; \
curl --location https://phar.phpunit.de/phpunit-5.7.27.phar > /usr/local/bin/phpunit5; \
curl --location https://phar.phpunit.de/phpunit-8.1.5.phar > /usr/local/bin/phpunit; \
chmod 700 /usr/local/bin/phpunit*; \
'

CMD test/run.sh ${PHP_VERSION}
