#!/bin/bash
PWD=$(pwd)
SCRIPT_WORKDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
BASENAME_LOWER=$(basename $SCRIPT_WORKDIR | tr '[:upper:]' '[:lower:]')

NETNAME=dev

APP_IMAGE_NAME=elastic-recipes
APP_CONTAINER_NAME=elastic-recipes-web
ELASTIC_CONTAINER_NAME=elastic-db

create_network_ifnotexists(){
    # -z is if output is empty
    if [ -z "$(docker network ls | grep $1)" ]; then
        docker network create $1
        echo "Network $1 created"
    fi
}

install(){
    build_image
    create_network_ifnotexists $NETNAME;

    composer

    elastic_up
    elastic_seed

    web_up
}

build_image(){

    # docker build -t $APP_IMAGE_NAME $SCRIPT_WORKDIR/docker/php
    docker build -t $APP_IMAGE_NAME ./docker/php
}

composer() {

    docker run -it --rm -v $SCRIPT_WORKDIR:/var/www $APP_IMAGE_NAME bash -c "composer install"
    # docker run -it --rm -v $SCRIPT_WORKDIR:/var/www $APP_IMAGE_NAME bash -c "composer update"
}

elastic_up() {

    docker run -d --network=$NETNAME \
    --name $ELASTIC_CONTAINER_NAME \
    -p 9200:9200 \
    -p 9300:9300 \
    -e "discovery.type=single-node" \
    -e "xpack.security.enabled=false" \
    docker.elastic.co/elasticsearch/elasticsearch:5.6.10
}

elastic_seed(){

    echo "waiting for elastic and seeding database"
    sleep 30

    docker run -it --rm \
    --network=$NETNAME \
    -v $SCRIPT_WORKDIR:/var/www \
    $APP_IMAGE_NAME bash -c "php docker/elastic/data/seed.php"
}

web_up(){

    docker run -d --network=$NETNAME \
    --name $APP_CONTAINER_NAME \
    -p 9999:80 \
    -v $SCRIPT_WORKDIR:/var/www \
    $APP_IMAGE_NAME
}

phpunit(){

    docker run -it --rm -v $SCRIPT_WORKDIR:/var/www $APP_IMAGE_NAME bash -c "vendor/bin/phpunit tests"
}

destroy(){

    docker stop $APP_CONTAINER_NAME && docker rm $APP_CONTAINER_NAME
    docker stop $ELASTIC_CONTAINER_NAME && docker rm $ELASTIC_CONTAINER_NAME
    docker network rm $NETNAME
    docker rmi $APP_IMAGE_NAME
}

$1
