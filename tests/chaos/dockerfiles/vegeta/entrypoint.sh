#!/usr/bin/env sh

set -euo

echo "Waiting for the service up"

RESPONSE=""
while [ "$RESPONSE" != "OK" ]
do
    echo "Response is: ${RESPONSE}"
    RESPONSE=$(curl -s http://nginx)
    sleep 1
done

DURATION=${DURATION:-30s}

echo "Running load tests for ${DURATION}."

echo "GET http://nginx" | vegeta attack -duration=${DURATION} | tee results.bin | vegeta report
