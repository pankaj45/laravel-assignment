
.PHONY: init
init:
	./vendor/bin/sail up
    docker-compose exec laravel.test php artisan migrate
    docker-compose exec laravel.test php artisan passport:install


