FROM alpine:latest

ARG S6_OVERLAY_VERSION=3.1.5.0

ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-x86_64.tar.xz /tmp

RUN apk -U upgrade && apk add --no-cache \
    curl \
    nginx \
    php82 \
    php82-curl \
    php82-dom \
    php82-fileinfo \
    php82-fpm \
    php82-gd \
    php82-iconv \
    php82-intl \
    php82-mbstring \
    php82-opcache \
    php82-pdo \
    php82-pdo_mysql \
    php82-phar \
    php82-session \
    php82-simplexml \
    php82-tokenizer \
    php82-xml \
    php82-zip \
    php82-ctype \
    php82-xmlreader \
    php82-xmlwriter \
    php82-openssl \
    && ln -s /usr/bin/php82 /usr/bin/php \
    && addgroup -S php \
    && adduser -S -G php php \
    && tar -C / -Jxpf /tmp/s6-overlay-x86_64.tar.xz  \
    && tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz \
    && rm -rf /var/cache/apk/* /tmp/s6-overlay-x86_64.tar.xz /tmp/s6-overlay-noarch.tar.xz  \
    && mkdir -p /run/nginx \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "copy('https://composer.github.io/installer.sig', 'signature');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('signature'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" 


ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.2.26/supercronic-linux-amd64 \
    SUPERCRONIC=supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=7a79496cf8ad899b99a719355d4db27422396735

RUN curl -fsSLO "$SUPERCRONIC_URL" \
 && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
 && chmod +x "$SUPERCRONIC" \
 && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
 && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic


RUN apk add \
        --no-cache \
        --repository http://dl-3.alpinelinux.org/alpine/edge/community/ --allow-untrusted \
        --virtual .shadow-deps \
        shadow \
    # && usermod -u 1000 www-data \
    # && groupmod -g 1000 www-data \
    && apk del .shadow-deps
    
# Set timezone
RUN ln -fs /usr/share/zoneinfo/Asia/Bishkek /etc/localtime
RUN rm -rf /var/cache/apk/*

ADD kube/crontab /etc/supercronic/crontab
COPY kube/etc /etc

RUN chmod -R 755 /etc/services.d

EXPOSE 80

COPY app /app
COPY kube/init.sh /app/init.sh

WORKDIR /app
RUN chmod -R guo+w /app/storage

RUN  composer update \
    && composer install \
    && true

ENTRYPOINT ["/init"]