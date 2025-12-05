#!/bin/bash

# FIX_AND_DEPLOY.sh
# Run this script on your VPS as root (or with sudo) to deploy changes and fix permissions.

echo "Starting Deployment Fix..."

# 1. Navigate to project directory
# Adjust this path if your project is somewhere else
PROJECT_DIR="/var/www/otorrinonet"
cd "$PROJECT_DIR" || { echo "Directory not found! Edit the script to set correct PROJECT_DIR."; exit 1; }

echo "Pulling latest changes..."
# Uncomment if you are pulling from git
# git pull origin main

# 2. Fix Permissions
echo "Fixing permissions..."
# Ensure web server owns the files
chown -R www-data:www-data .
# Ensure permissions are correct for Laravel
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/build

# 3. Install JS Dependencies
echo "Installing JS dependencies..."
# This requires Node.js/npm to be installed
npm install

# 4. Build Assets
echo "Building frontend assets..."
npm run build

# 5. Clear Caches
echo "Clearing Laravel caches..."
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# 6. Re-optimize
echo "Optimizing..."
php artisan optimize

echo "Deployment Fix Complete!"
echo "If you still see a white screen, check your Nginx configuration."

#!/bin/bash

# FIX_AND_DEPLOY.sh
# Run this script on your VPS as root (or with sudo) to deploy changes and fix permissions.

echo "Starting Deployment Fix..."

# 1. Navigate to project directory
# Adjust this path if your project is somewhere else
PROJECT_DIR="/var/www/otorrinonet"
cd "$PROJECT_DIR" || { echo "Directory not found! Edit the script to set correct PROJECT_DIR."; exit 1; }

echo "Pulling latest changes..."
# Uncomment if you are pulling from git
# git pull origin main

# 2. Fix Permissions
echo "Fixing permissions..."
# Ensure web server owns the files
chown -R www-data:www-data .
# Ensure permissions are correct for Laravel
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/build

# 3. Install JS Dependencies
echo "Installing JS dependencies..."
# This requires Node.js/npm to be installed
npm install

# 4. Build Assets
echo "Building frontend assets..."
npm run build

# 5. Clear Caches
echo "Clearing Laravel caches..."
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# 6. Re-optimize
echo "Optimizing..."
php artisan optimize

echo "Deployment Fix Complete!"
echo "If you still see a white screen, check your Nginx configuration."
