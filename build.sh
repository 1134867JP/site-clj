#!/bin/bash
set -e

echo "🔧 Starting build process for Render.com..."

# Copy environment file if .env doesn't exist
if [ ! -f .env ]; then
    echo "📄 Setting up environment file..."
    if [ -f .env.render ]; then
        cp .env.render .env
    else
        cp .env.example .env
    fi
fi

# Install PHP dependencies (production only, without requiring GitHub tokens)
echo "📦 Installing PHP dependencies..."
export COMPOSER_ALLOW_SUPERUSER=1
export COMPOSER_NO_INTERACTION=1
composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# Install Node.js dependencies (including dev dependencies for build)
echo "📦 Installing Node.js dependencies..."
npm ci --no-audit

# Build frontend assets
echo "🏗️ Building frontend assets..."
npm run build

# Create required directories with proper permissions
echo "📁 Setting up directories and permissions..."
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create SQLite database file if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    echo "🗃️ Creating SQLite database..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
fi

# Generate application key if not set
echo "🔑 Setting up application key..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Clear and cache configuration for production
echo "⚡ Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if database is available
echo "🗃️ Running database migrations..."
php artisan migrate --force --no-interaction || echo "⚠️ Database migrations skipped (database not available)"

# Create storage symlink
php artisan storage:link || echo "ℹ️ Storage link already exists"

echo "✅ Build completed successfully!"