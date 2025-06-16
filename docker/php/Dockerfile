FROM php:8.2-fpm

# Arguments définis dans docker-compose.yml
ARG user
ARG uid

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Nettoyer le cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un utilisateur système pour exécuter les commandes Composer et Artisan
RUN groupadd -g 1000 www-data || true
RUN useradd -u 1000 -ms /bin/bash -g www-data www-data || true
RUN mkdir -p /home/www-data/.composer && \
    chown -R www-data:www-data /home/www-data

# Définir le répertoire de travail
WORKDIR /var/www/html

USER www-data
