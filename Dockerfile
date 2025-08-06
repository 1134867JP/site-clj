# Use a base image com PHP e Apache
FROM php:8.2-apache

# Instale dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instale o Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Instale o Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Configure o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos do projeto para o contêiner
COPY . .

# Instale as dependências do PHP e do Node.js
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Configure permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Aponta o Apache para a pasta "public"
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Evita aviso de ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exponha a porta 80
EXPOSE 80

# Comando para iniciar o servidor Apache
CMD ["apache2-foreground"]
