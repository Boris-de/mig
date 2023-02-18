#!/bin/sh -e

PHP_VERSION="${1}"
PHPUNIT_VERSION=10
TEST_DIR="test"

case "${PHP_VERSION}" in
  5.*|7.0.*|7.1.*)
    PHPUNIT_VERSION=5
    TEMP=$(mktemp -d)
    cp -r "${TEST_DIR}" "${TEMP}"
    TEST_DIR="${TEMP}/${TEST_DIR}"
    sed -i -e 's#: void##' "${TEST_DIR}"/*.php
   ;;
  7.*|8.0.*)
    PHPUNIT_VERSION=8
   ;;
esac

PHPUNIT=$(find /usr/local/bin/ -name "phpunit-${PHPUNIT_VERSION}"* | sort | tail -n 1)
test -z "${PHPUNIT}" && { echo "Could not find ${PHPUNIT_VERSION}"; exit 1; }
test -e "${PHPUNIT}" || { echo "Could not find ${PHPUNIT_VERSION}: ${PHPUNIT}"; exit 1; }
echo "Using ${PHPUNIT} (for version \"${PHPUNIT_VERSION}\") to run tests for PHP ${PHP_VERSION}"
exec "${PHPUNIT}" --include-path functions/:main/:languages/ "${TEST_DIR}"
