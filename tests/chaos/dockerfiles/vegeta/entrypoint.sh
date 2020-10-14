#!/usr/bin/env sh

set -euo

DURATION=${DURATION:-30s}

echo "Running load tests for ${DURATION}."

echo "GET http://nginx" | vegeta attack -duration=${DURATION} | tee results.bin | vegeta report
