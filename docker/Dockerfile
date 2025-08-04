FROM php:8.2-fpm

COPY ./root /root
COPY ./src /src

WORKDIR /root

RUN  apt update \
  && apt install -y \
    supervisor \
    unzip \
    zlib1g-dev \
    libpng-dev \
    vim \
    nano \
  && curl -sS https://getcomposer.org/installer -o composer-setup.php \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && chmod +x /root/*.sh \
  && composer global require "friendsofphp/php-cs-fixer" \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install gd \
  && php /src/change-entrypoint.php

COPY supervisor.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
