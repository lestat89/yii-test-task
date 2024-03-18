FROM php:8.2-fpm

ARG USER_ID
ARG GROUP_ID

RUN apt-get update

RUN apt-get install -y \
        apt-utils \
        lsb-release \
        libxml2-dev \
        git \
        curl \
        nano \
        unzip \
        locales

RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen \
    && echo 'LANG="en_US.UTF-8"'>/etc/default/locale \
    && dpkg-reconfigure --frontend=noninteractive locales \
    && update-locale LANG=en_US.UTF-8
ENV LANG en_US.UTF-8

RUN ln -snf /usr/share/zoneinfo/Europe/Kiev /etc/localtime \
    && echo "Europe/Kiev" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

RUN ln -sf /usr/local/bin/php /usr/bin/php

RUN if [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ]; then \
        userdel -f www-data && \
        if getent group www-data ; then groupdel www-data; fi && \
        groupadd -g ${GROUP_ID} www-data && \
        useradd -l -u ${USER_ID} -g www-data -m -s /bin/bash www-data && \
        install -d -m 0755 -o www-data -g www-data /home/www-data && \
        chown --changes --silent --no-dereference --recursive \
              --from=33:33 ${USER_ID}:${GROUP_ID} \
            /home/www-data \
    ;fi

RUN apt-get clean \
    && rm -r /var/lib/apt/lists/*

USER www-data

CMD php-fpm
