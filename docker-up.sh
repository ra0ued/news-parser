#!/bin/sh

nginx_port="8087"
xdebug_port="9090"
xdebug_enabled=""

while test $# -gt 0; do
    case "$1" in
        -n|--nginx-port)
            shift
            if [ ! -z "$1" ]; then
                nginx_port=$1
            fi
            shift
            ;;
        --xdebug-port)
            shift
            if [ ! -z "$1" ]; then
                xdebug_port=$1
            fi
            shift
            ;;
        --xdebug-enabled)
            shift
            if [ ! -z "$1" ]; then
                xdebug_enabled="RUN pecl install xdebug-2.5.0 \&\& docker-php-ext-enable xdebug"
            fi
            shift
            ;;
        *)
            echo "Unknown $1 param"
            exit
            break
            ;;
    esac
done

# avoid permission conflicts with user at host machine
uid=$(id -u)
if [ $uid -gt 100000 ]; then
	uid=1000
fi

cp ./docker/app/Dockerfile.dist ./docker/app/Dockerfile
sed -i "s/\$XDEBUG_INSTALL/$xdebug_enabled/g" ./docker/app/Dockerfile
sed -i "s/\$USER_ID/$uid/g" ./docker/app/Dockerfile

cp ./docker-compose.yml.dist ./docker-compose.yml
sed -i "s/\$NGINX_PORT/$nginx_port/g" ./docker-compose.yml
sed -i "s/\$XDEBUG_REMOTE_PORT/$xdebug_port/g" ./docker-compose.yml
sed -i "s/\$POSTGRES_PORT/$postgres_port/g" ./docker-compose.yml

docker-compose build
docker-compose up -d

docker exec -u www-data bbc_app bash -c "composer install"
docker exec -u www-data bbc_app bash -c "php app/console doctrine:database:create --if-not-exists"
docker exec -u www-data bbc_app bash -c "php app/console doctrine:schema:update --force"