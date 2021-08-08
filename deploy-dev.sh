set -e
echo "Deploying on docker DEV"

git reset --hard develop
git fetch
git pull origin develop
docker-compose up --build -d
docker-compose run --rm artisan migrate
docker-compose run --rm artisan cache:clear
docker-compose run --rm artisan config:clear
docker-compose run --rm artisan config:cache
docker-compose run --rm composer install
docker-compose run --rm composer dumpautoload
docker-compose run --rm artisan l5-swagger:generate

echo "application deployed DEV"
