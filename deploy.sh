set -e
echo "Deploying"

git fetch
git pull origin develop
php artisan migrate
php artisan cache:clear
php artisan config:clear
php artisan config:cache
composer dumpautoload

echo "application deployed"
