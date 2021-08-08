set -e
echo "Deploying on docker DEV"

git reset --hard develop
git fetch
git pull origin develop

docker-compose -f docker-compose-dev.yml up --build -d
docker-compose -f docker-compose-dev.yml run --rm artisan migrate
docker-compose -f docker-compose-dev.yml run --rm artisan cache:clear
docker-compose -f docker-compose-dev.yml run --rm artisan config:clear
docker-compose -f docker-compose-dev.yml run --rm artisan config:cache
docker-compose -f docker-compose-dev.yml run --rm composer install
docker-compose -f docker-compose-dev.yml run --rm composer dumpautoload
docker-compose -f docker-compose-dev.yml run --rm artisan l5-swagger:generate

echo "application deployed DEV"
