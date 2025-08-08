#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Ensure proper permissions are set
chmod -R 775 storage bootstrap/cache

# Start the Laravel application using the built-in PHP server
# Render.com will handle the port through the PORT environment variable
php -S 0.0.0.0:${PORT:-8000} -t public