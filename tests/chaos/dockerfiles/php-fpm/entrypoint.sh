#!/usr/bin/env sh

set -euo

if [ ! -z "${TRACER_DOWNLOAD_URL}" ]; then
    curl -o /tmp/dd-trace-php.deb -L ${TRACER_DOWNLOAD_URL}
    dpkg -i /tmp/dd-trace-php.deb
fi

# Enabling core dumps
echo '/coredumps/core' > /proc/sys/kernel/core_pattern

php-fpm
