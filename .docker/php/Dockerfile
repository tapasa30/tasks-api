FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    libzip-dev \
    libmagickwand-dev \
    gnupg2 \
    rsync \
    vim \
    ca-certificates \
    wget \
    tar

RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    opcache \
    zip

RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install gd

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy PHP config
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
