# Clear application cache (optional, depending on your application's requirements)
php artisan cache:clear

# Install dependencies using composer.lock (prevents unnecessary updates)
composer install --no-dev --prefer-dist

# Apply database migrations (if applicable)
php artisan migrate --force

# Clear compiled views and configuration (optional)
php artisan view:clear
php artisan config:cache