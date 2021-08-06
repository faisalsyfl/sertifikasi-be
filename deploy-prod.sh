set -e
echo "Deploying on docker PROD"

git reset --hard master
git fetch
git pull origin master

docker-compose run --rm api_sifion_artisan_prod migrate
docker-compose run --rm api_sifion_artisan_prod cache:clear
docker-compose run --rm api_sifion_artisan_prod config:clear
docker-compose run --rm api_sifion_artisan_prod config:cache
docker-compose run --rm api_sifion_composer_prod install
docker-compose run --rm api_sifion_composer_prod dumpautoload
docker-compose run --rm api_sifion_artisan_prod l5-swagger:generate

echo "application deployed PROD"
