set -e
echo "Deploying on docker PROD"

git reset --hard master
git fetch
git pull origin master

docker-compose -f docker-compose-prod.yml build --pull --no-cache
docker-compose -f docker-compose-prod.yml up -d
docker-compose -f docker-compose-prod.yml run --rm api_sifion_composer_prod install
docker-compose -f docker-compose-prod.yml run --rm api_sifion_composer_prod dumpautoload
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod migrate --force
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod cache:clear
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod config:clear
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod config:cache
docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod l5-swagger:generate
# docker-compose -f docker-compose-prod.yml run --rm api_sifion_artisan_prod storage:link
docker exec -ti api_sifion_nginx_prod sh -c "chmod -R 775 /var/www/html/public"

echo "application deployed PROD"