# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target


# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG PHP_VERSION=8.0

# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine


# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git

ARG APCU_VERSION=5.1.19

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
		libpng \
		libpng-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
	pdo \
	pdo_mysql \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
  pecl install xdebug-3.0.0; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

###> recipes ###
###< recipes ###
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

COPY php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN install-php-extensions gd

#6.ADD-CRONTAB
RUN mkdir -p /etc/periodic/everymin
COPY ./tasks.cron /tmp/tasks
RUN cat /tmp/tasks >> /etc/crontabs/root
RUN echo -e "extension=gd.so" > /usr/local/etc/php/conf.d/docker-php-ext-gd.ini
RUN mkdir -p /var/log/cron \
 && touch /var/log/cron/cron.log

VOLUME /var/run/php

WORKDIR /var/www

# build for production
ARG APP_ENV=prod

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENV SYMFONY_PHPUNIT_VERSION=9

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

WORKDIR /var/www
