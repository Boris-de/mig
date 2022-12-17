#!/bin/sh

set -e -u

die() {
  echo "${@}" 1>&2
  exit 1
}

cleanup() {
  echo "Cleaning up"
  rm -rf "${TEMP_DIR}"
}

test -z "${1}" && die "usage: ${0} archive"
ARCHIVE=${1}
PORT=${2-"8000"}

TEMP_DIR=$(mktemp -d)
trap cleanup EXIT

tar xf "${ARCHIVE}" -C "${TEMP_DIR}"
mv "${TEMP_DIR}"/*/* "${TEMP_DIR}"
cp "${TEMP_DIR}/config.php.default" "${TEMP_DIR}/config.php"

"$(dirname "${0}")/create-random-album.sh" "${TEMP_DIR}/albums/"

echo "Running server on localhost:${PORT}, use ctrl-c to abort testing"

php --server "localhost:${PORT}" --docroot "${TEMP_DIR}"
