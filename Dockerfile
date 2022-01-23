FROM ubuntu:focal AS builder
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
        git \
        php-cli \
        php-pgsql \
        php-mysql \
        php-dom \
        php-zip \
        composer && \
    rm -rf /var/lib/apt/lists/*
COPY . /shadowd_ui
WORKDIR /shadowd_ui
RUN composer -n install --no-dev --no-scripts && \
    chown -R www-data:www-data app/cache app/logs


FROM ubuntu:focal
MAINTAINER Hendrik Buchwald
ENV SYMFONY_ENV prod
EXPOSE 80
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        lighttpd \
        php-cgi \
        php-cli \
        php-pgsql \
        php-mysql \
        php-dom \
        php-zip && \
    rm -rf /var/lib/apt/lists/*
COPY --from=builder /shadowd_ui /var/shadowd_ui
WORKDIR /var/shadowd_ui
RUN mv misc/docker/docker-entrypoint.sh / && \
    mv misc/docker/lighttpd.conf /etc/lighttpd && \
    mv misc/docker/parameters.yml.dist app/config/parameters.yml && \
    ln -s /etc/lighttpd/conf-available/10-fastcgi.conf /etc/lighttpd/conf-enabled && \
    ln -s /etc/lighttpd/conf-available/15-fastcgi-php.conf /etc/lighttpd/conf-enabled && \
    mkdir /var/run/lighttpd && \
    chown www-data:www-data /var/run/lighttpd && \
    sed -i \
        "s/error_reporting = .*/error_reporting = E_ALL \& ~E_DEPRECATED \& ~E_STRICT \& ~E_WARNING/g" \
        /etc/php/7.4/cli/php.ini
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["/usr/sbin/lighttpd", "-f", "/etc/lighttpd/lighttpd.conf", "-D"]
