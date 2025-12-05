#!/bin/bash

# Make the script exit immediately if a command exits with a non-zero status.
set -e

# FIX_AND_DEPLOY.sh
# Run this script on your VPS as root (or with sudo) to deploy changes and fix permissions.

echo "Starting Deployment Fix..."

# 1. Define Variables
PROJECT_DIR="/var/www/otorrinonet"
PHP_USER="www-data"

# 2. Navigate to project directory
cd "$PROJECT_DIR" || { echo "Directory not found! Edit the script to set correct PROJECT_DIR."; exit 1; }

echo "Pulling latest changes..."
# It's good practice to specify the branch you are deploying.
# git pull origin main

# 3. Put application in maintenance mode
php artisan down || true

# 4. Install/Update Composer Dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 5. Install JS Dependencies
echo "Installing JS dependencies..."
# This requires Node.js/npm to be installed
npm install

# 6. Build Assets
echo "Building frontend assets..."
npm run build

# 7. Run Database Migrations
echo "Running database migrations..."
php artisan migrate --force

# 8. Clear and Re-cache
echo "Clearing and caching..."
php artisan optimize:clear
php artisan optimize

# 9. Fix Permissions
echo "Fixing permissions..."
# Ensure web server owns the files
chown -R "$PHP_USER":"$PHP_USER" .
# Ensure permissions are correct for Laravel
find . -type f -exec chmod 664 {} \;
find . -type d -exec chmod 775 {} \;
chmod -R ug+rwx storage bootstrap/cache

# 10. Bring application back online
php artisan up

echo "Deployment Fix Complete!"
echo "If you still see a white screen, check your Nginx configuration."
