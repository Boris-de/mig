#!/bin/sh -e

TEST_DIR="test"

exec composer exec phpunit -- --include-path functions/:main/:languages/ "${TEST_DIR}"
