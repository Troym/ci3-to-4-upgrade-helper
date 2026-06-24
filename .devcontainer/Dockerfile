FROM php:8.2-cli-bookworm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by CodeIgniter 4 and this project
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
    gd \
    intl \
    xml \
    zip \
    && docker-php-ext-enable gd intl xml zip

# Install xdebug for coverage (composer script: coverage)
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install pcov for fast coverage (composer script: pcov)
RUN pecl install pcov && docker-php-ext-enable pcov

# Configure xdebug (mode off by default; override per-run with -dzend_extension=xdebug.so)
RUN echo "xdebug.mode=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Create non-root user to match devcontainer convention
RUN groupadd --gid 1000 vscode \
    && useradd --uid 1000 --gid vscode --shell /bin/bash --create-home vscode

USER vscode
