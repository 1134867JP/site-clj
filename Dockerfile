# ============== Base: PHP + Apache ==============
FROM php:8.2-apache

# ---- Dependências do sistema e extensões PHP ----
RUN apt-get update && apt-get install -y \
    git unzip curl \
    libpng-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev \
    && docker-php-ext-install -j$(nproc) pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# ---- Composer (como root, permitido) ----
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# ---- Node.js 20 + npm ----
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# ---- Apache: docroot em /public e .htaccess habilitado ----
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    printf '\n<Directory "/var/www/html/public">\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

# ============== Fase de cache de dependências ==============

# ---- PHP deps (cache) ----
COPY composer.json composer.lock ./ 
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

# ---- Node deps (cache) ----
COPY package.json package-lock.json* ./ 
RUN npm ci || npm install

# ============== Código da aplicação ==============
COPY . .

# ---- Composer scripts que precisam do artisan ----
RUN composer dump-autoload -o && php artisan package:discover --ansi || true

# ---- Build dos assets (Vite) ----
RUN npm run build

# ---- Permissões ----
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80

# ---- Início com migrações ----
CMD php artisan config:clear && \
    php artisan migrate --force && \
    apache2-foreground