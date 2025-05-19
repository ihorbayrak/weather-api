FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Copy composer files early to cache dependencies
COPY composer.lock composer.json ./

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    unzip \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev \
    libpq-dev \
    supervisor \
    libicu-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring zip exif pcntl intl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install -j$(nproc) gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add non-root user
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# Copy application source
COPY . /var/www
COPY --chown=www:www . /var/www

# Supervisor setup
ADD docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN mkdir -p /run/supervisor /var/log/supervisor && \
    chown -R www:www-data /run/ && \
    chown -R www:www-data /var/log/supervisor && \
    chown -R www:www-data /etc/supervisor/conf.d/

RUN mkdir -p var/cache var/log && \
    chown -R www:www-data var && \
    chmod -R 775 var

USER www

EXPOSE 9000
ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/conf.d/supervisor.conf"]
