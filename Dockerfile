# PHP + Apache
FROM php:8.2-apache

# Dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js 20 + npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Apache: docroot em /public e permitir .htaccess
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    printf '\n<Directory "/var/www/html/public">\n    AllowOverride All\n</Directory>\n' >> /etc/apache2/apache2.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

# ---- Cache de dependências ----
# Composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Node/Vite
COPY package.json package-lock.json* ./
RUN npm ci || npm install

# ---- Código da aplicação ----
COPY . .

# Build dos assets (usa Vite)
RUN npm run build

# Permissões
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]