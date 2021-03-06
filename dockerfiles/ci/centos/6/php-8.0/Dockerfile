FROM centos:6 as base

RUN set -eux; \
    yum update -y; \
    yum install -y \
        centos-release-scl \
        curl \
        environment-modules \
        gcc \
        gcc-c++ \
        git \
        libedit-devel \
        make \
        openssl-devel \
        pkg-config \
        postgresql-devel \
        readline-devel \
        scl-utils \
        unzip \
        vim \
        xz \
        zlib-devel; \
    yum install -y devtoolset-7; \
    yum clean all;

ENV SRC_DIR=/usr/local/src

COPY download-src.sh /root/
RUN set -eux; \
# Latest version of m4 required
    /root/download-src.sh m4 https://ftp.gnu.org/gnu/m4/m4-1.4.18.tar.gz; \
    cd "${SRC_DIR}/m4"; \
    ./configure && make && make install; \
# Latest version of autoconf required
    /root/download-src.sh autoconf https://ftp.gnu.org/gnu/autoconf/autoconf-2.69.tar.gz; \
    cd "${SRC_DIR}/autoconf"; \
    ./configure && make && make install; \
# Requested 'libxml-2.0 >= 2.9.0' but version of libXML is 2.7.6
    /root/download-src.sh libxml2 http://xmlsoft.org/sources/libxml2-2.9.10.tar.gz; \
    cd "${SRC_DIR}/libxml2"; \
    ./configure --with-python=no; \
    make && make install; \
# Requested 'libcurl >= 7.29.0' but version of libcurl is 7.19.7
    /root/download-src.sh libcurl https://curl.haxx.se/download/curl-7.72.0.tar.gz; \
    cd "${SRC_DIR}/libcurl"; \
    ./configure && make && make install; \
# No package 'oniguruma' found
    /root/download-src.sh oniguruma https://github.com/kkos/oniguruma/releases/download/v6.9.5_rev1/onig-6.9.5-rev1.tar.gz; \
    cd "${SRC_DIR}/oniguruma"; \
    ./configure && make && make install; \
# bison 3.0.0 or later is required to generate PHP parsers
    /root/download-src.sh bison https://ftp.gnu.org/gnu/bison/bison-3.7.3.tar.gz; \
    cd "${SRC_DIR}/bison"; \
    ./configure && make && make install; \
# re2c 0.13.4 is required to generate PHP lexers
    /root/download-src.sh re2c https://github.com/skvadrik/re2c/releases/download/2.0.3/re2c-2.0.3.tar.xz; \
    cd "${SRC_DIR}/re2c"; \
    ./configure && make && make install;

# CMake 3.0.2 or higher is required.  You are running version 2.8.12.2
# Required to build libzip from source (has to be a separate RUN layer)
RUN source scl_source enable devtoolset-7; \
    set -eux; \
    /root/download-src.sh cmake https://github.com/Kitware/CMake/releases/download/v3.18.4/cmake-3.18.4.tar.gz; \
    cd "${SRC_DIR}/cmake"; \
    ./bootstrap && make && make install; \
# Requested 'libzip >= 0.11' but version of libzip is 0.9
    /root/download-src.sh libzip https://libzip.org/download/libzip-1.7.3.tar.gz; \
    cd "${SRC_DIR}/libzip"; \
    mkdir build && cd build; \
    cmake .. && make && make install;

ENV PHP_SRC_DIR=/usr/local/src/php
ENV PHP_INSTALL_DIR=/opt/php

ARG phpVersion
ENV PHP_INSTALL_DIR_ZTS=${PHP_INSTALL_DIR}/${phpVersion}-zts
ENV PHP_INSTALL_DIR_DEBUG_NTS=${PHP_INSTALL_DIR}/${phpVersion}-debug
ENV PHP_INSTALL_DIR_NTS=${PHP_INSTALL_DIR}/${phpVersion}
ENV PHP_VERSION=${phpVersion}

# Download and extract PHP source
ARG phpTarGzUrl
ARG phpSha256Hash
RUN set -eux; \
    mkdir -p $PHP_SRC_DIR; \
    mkdir -p $PHP_INSTALL_DIR; \
    curl -fsSL -o /tmp/php.tar.gz "${phpTarGzUrl}"; \
    (echo "${phpSha256Hash} /tmp/php.tar.gz" | sha256sum -); \
    tar xf /tmp/php.tar.gz -C "${PHP_SRC_DIR}" --strip-components=1; \
    rm -f /tmp/php.tar.gz; \
    ${PHP_SRC_DIR}/buildconf --force;

ENV PKG_CONFIG_PATH="${PKG_CONFIG_PATH}:/usr/local/lib/pkgconfig:/usr/local/lib64/pkgconfig"

FROM base as build
COPY configure.sh /root/

FROM build as php-zts
RUN set -eux; \
    mkdir -p /tmp/build-php && cd /tmp/build-php; \
    /root/configure.sh \
        --enable-zts \
        --prefix=${PHP_INSTALL_DIR_ZTS} \
        --with-config-file-path=${PHP_INSTALL_DIR_ZTS} \
        --with-config-file-scan-dir=${PHP_INSTALL_DIR_ZTS}/conf.d; \
    make -j "$((`nproc`+1))"; \
    make install; \
    mkdir -p ${PHP_INSTALL_DIR_ZTS}/conf.d;

FROM build as php-debug
RUN set -eux; \
    mkdir -p /tmp/build-php && cd /tmp/build-php; \
    /root/configure.sh \
        --enable-debug \
        --prefix=${PHP_INSTALL_DIR_DEBUG_NTS} \
        --with-config-file-path=${PHP_INSTALL_DIR_DEBUG_NTS} \
        --with-config-file-scan-dir=${PHP_INSTALL_DIR_DEBUG_NTS}/conf.d; \
    make -j "$((`nproc`+1))"; \
    make install; \
    mkdir -p ${PHP_INSTALL_DIR_DEBUG_NTS}/conf.d;

FROM build as php-nts
RUN set -eux; \
    mkdir -p /tmp/build-php && cd /tmp/build-php; \
    /root/configure.sh \
        --prefix=${PHP_INSTALL_DIR_NTS} \
        --with-config-file-path=${PHP_INSTALL_DIR_NTS} \
        --with-config-file-scan-dir=${PHP_INSTALL_DIR_NTS}/conf.d; \
    make -j "$((`nproc`+1))"; \
    make install; \
    mkdir -p ${PHP_INSTALL_DIR_NTS}/conf.d;

FROM base as final
COPY --from=php-zts $PHP_INSTALL_DIR_ZTS $PHP_INSTALL_DIR_ZTS
COPY --from=php-debug $PHP_INSTALL_DIR_DEBUG_NTS $PHP_INSTALL_DIR_DEBUG_NTS
COPY --from=php-nts $PHP_INSTALL_DIR_NTS $PHP_INSTALL_DIR_NTS

RUN printf "source scl_source enable devtoolset-7" | tee -a /etc/profile.d/zzz-ddtrace.sh /etc/bashrc
ENV BASH_ENV="/etc/profile.d/zzz-ddtrace.sh"

COPY switch-php /usr/local/bin/
RUN set -eux; \
# Set the default PHP version
    switch-php ${PHP_VERSION}-debug;
