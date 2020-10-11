#!/bin/sh -ex

PHP_VERSION="${1}"
PHPUNIT="phpunit"
TEST_DIR="test"

case "${PHP_VERSION}" in
  5.*|7.0.*|7.1.*)
    PHPUNIT=phpunit5
    TEMP=$(mktemp -d)
    cp -r "${TEST_DIR}" "${TEMP}"
    TEST_DIR="${TEMP}/${TEST_DIR}"
    sed -i -e 's#: void##' "${TEST_DIR}"/*.php
   ;;
  7.2.*)
    PHPUNIT=phpunit8
   ;;
esac

exec ${PHPUNIT} --include-path functions/:main/:languages/ "${TEST_DIR}"/*Test.php
