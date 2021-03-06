set -e
echo "Deploying"

git reset --hard
git fetch
git pull origin develop
php artisan migrate
php artisan cache:clear
php artisan config:clear
php artisan config:cache
composer install
composer dumpautoload
php artisan l5-swagger:generate

echo "application deployed"
