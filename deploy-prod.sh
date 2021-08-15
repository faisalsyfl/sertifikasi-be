set -e
echo "Deploying on docker PROD"

git reset --hard master
git fetch
git pull origin master

docker-compose -f docker-compose-prod.yml build --pull --no-cache
docker-compose -f docker-compose-prod.yml up -d
docker-compose -f docker-compose-prod.yml run --rm composer install
docker-compose -f docker-compose-prod.yml run --rm composer dumpautoload
docker-compose -f docker-compose-prod.yml run --rm artisan migrate
docker-compose -f docker-compose-prod.yml run --rm artisan cache:clear
docker-compose -f docker-compose-prod.yml run --rm artisan config:clear
docker-compose -f docker-compose-prod.yml run --rm artisan config:cache
docker-compose -f docker-compose-prod.yml run --rm artisan l5-swagger:generate
docker-compose -f docker-compose-prod.yml run --rm artisan storage:link
docker exec -i api_sifion_nginx_prod sh -c "chmod -R 775 /var/www/html/public"

echo "application deployed PROD"
