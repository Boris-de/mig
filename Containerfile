ARG PHP_VERSION=''

FROM docker.io/php:${PHP_VERSION}apache

EXPOSE 80

ARG WWW_DATA=/var/www/html/
ENV WWW_DATA=$WWW_DATA

COPY templates $WWW_DATA/templates/
COPY images $WWW_DATA/images/
COPY test-album $WWW_DATA/albums/
COPY build/index.php config.php $WWW_DATA

RUN /bin/sh -c 'set -e; \
sed -i -e "s#^[/ ]*\$error_reporting.*#\$error_reporting = E_ALL & ~E_NOTICE;#" ${WWW_DATA}/config.php; \
chown -R www-data ${WWW_DATA}; \
echo "<?php phpinfo(); ?>" > ${WWW_DATA}/phpinfo.php \
'
