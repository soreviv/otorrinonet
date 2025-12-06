#!/bin/bash
set -euo pipefail

# FIX_AND_DEPLOY.sh
# Run this script on your VPS as root (or with sudo) to deploy changes and fix permissions.

echo "Starting Deployment Fix..."

# 1. Navigate to project directory
# Adjust this path if your project is somewhere else
PROJECT_DIR="/var/www/otorrinonet"
cd "$PROJECT_DIR" || { echo "Directory not found! Edit the script to set correct PROJECT_DIR."; exit 1; }

echo "Pulling latest changes..."
# git pull is disabled by default to prevent overwriting local changes.
# To enable automated updates:
# 1. Ensure you are on the correct branch (e.g., main or fix/restore-landing-page)
# 2. Uncomment the line below:
# git pull origin main
#
# WARNING: Always verify you have no uncommitted changes before pulling.

# 2. Fix Permissions
echo "Fixing permissions..."
# Reset all file permissions to safe defaults (644 for files, 755 for dirs)
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Make executables executable
chmod +x artisan
chmod +x FIX_AND_DEPLOY.sh

# Give web server ownership ONLY to runtime directories
# Adjust 'www-data' if your web server user is different
chown -R www-data:www-data storage bootstrap/cache public public/build

# Set group write permissions for runtime directories
chmod -R 775 storage bootstrap/cache public public/build

# 3. Install JS Dependencies
echo "Installing JS dependencies..."
if ! npm install; then
    echo "Error: npm install failed!" >&2
    exit 1
fi

# 4. Build Assets
echo "Building frontend assets..."
if ! npm run build; then
    echo "Error: npm run build failed!" >&2
    exit 1
fi

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
