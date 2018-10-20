docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up --build -d

test:
	docker-compose exec php-cli vendor/bin/phpunit

migrate:
	docker-compose exec php-cli php artisan migrate

migrate-fresh:
	docker-compose exec php-cli php artisan migrate:fresh

migrate-seed:
	docker-compose exec php-cli php artisan migrate:fresh --seed

perm:
	sudo chgrp -R www-data storage bootstrap/cache
	sudo chmod -R ug+rwx storage bootstrap/cache