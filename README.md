### Starting Project
```
docker-compose up -d
docker exec -it test_php composer install
cp .env.example .env
docker exec -it test_php php artisan key:generate
docker exec -it test_php php artisan migrate
```
