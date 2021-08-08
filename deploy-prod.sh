set -e
echo "Deploying on docker PROD"

git reset --hard master
git fetch
git pull origin master

docker-compose -f docker-compose-prod.yml up --build -d
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod migrate
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod cache:clear
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod config:clear
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod config:cache
docker-compose -f docker-compose-prod.yml run --rm api_sifion_composer_prod install
docker-compose -f docker-compose-prod.yml run --rm api_sifion_composer_prod dumpautoload
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod l5-swagger:generate

echo "application deployed PROD"
