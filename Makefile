# Построение контейнеров для проекта + их запуск:
build-containers:
	docker compose build --no-cache
	@make docker-up

# Подготовка проекта к работе:
build-project:
	cp .env.example .env && \
	docker compose exec -u root -t -i php bash -c "composer install" && \
	docker compose exec -u root -t -i php bash -c "php artisan key:generate" && \
	docker compose exec -u root -t -i php bash -c "sudo chmod -R 777 docker/volume" && \
	docker compose exec -u root -t -i php bash -c "sudo chmod -R 777 bootstrap/cache" && \
	docker compose exec -u root -t -i php bash -c "sudo chmod -R 777 storage/framework" && \
	docker compose exec -u root -t -i php bash -c "sudo chmod -R 777 storage/logs" && \
	docker compose exec -u root -t -i php bash -c "php artisan storage:link" && \
	docker compose exec php bash -c "npm i" && \
	docker compose exec php bash -c "npm run dev"

# Заполнение БД таблицами из миграций:
migrate:
	docker compose exec -u root -t -i php bash -c "php artisan migrate" #&& \
	#docker compose exec -u root -t -i php bash -c "php artisan tenants:migrate"
	#docker compose exec -u root -t -i php bash -c "php artisan migrate:fresh --seed"

# Заполнение БД данными:
seed:
	docker compose exec -u root -t -i php bash -c "php artisan db:seed" #&& \
	#docker compose exec -u root -t -i php bash -c "php artisan tenants:seed"

docker-up:
	docker compose up -d

# Создание dev-сборки CSS и JS файлов via Vite:
npm-dev:
	docker compose exec php bash -c "npm run dev"

# Создание prod-сборки CSS и JS файлов via Vite:
npm-prod:
	docker compose exec php bash -c "npm run prod"

# Переход в PHP-контейнер (в нём выполняются все терминальные команды в проекте):
php:
	docker compose exec -u root -t -i php bash

# Переход в MYSQL-контейнер (пример перехода в конкретную БД ---> make mysql DB=endless_profile_v3 ):
mysql:
	docker compose exec mysql bash -c 'mysql -u root -proot $(DB)'

# Переход в MYSQL-контейнер и конкретную БД "endless_profile_v3" + импорт дампа из "docker/volume/dumps/endless_profile_v3.sql":
mysql-import-dump-main:
	docker compose exec mysql bash -c 'mysql -u root -proot endless_profile_v3 < /var/dumps/endless_profile_v3.sql'

# Переход в MYSQL-контейнер и конкретную БД "endless_profile_v3" + экспорт дампа в файл "docker/volume/dumps/endless_profile_v3.sql":
mysql-export-dump-main:
	docker compose exec mysql bash -c 'mysqldump -u root -proot endless_profile_v3 > /var/dumps/endless_profile_v3.sql'

