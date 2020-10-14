#!/usr/bin/env sh

set -euo

# Enabling core dumps
echo '/coredumps/core' > /proc/sys/kernel/core_pattern

php-fpm
