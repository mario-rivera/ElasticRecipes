## Prerequsites
- Uses [Docker](https://www.docker.com/products/docker) to deploy the application.
- Install [Docker](https://docs.docker.com/engine/installation)

## Installation Linux or MacOS
- Run the installation script "bash ./install.sh install"
- This will build the docker image and run the containers

## Run Unit Tests
- Run "bash ./install.sh phpunit"

## To Find a Recipe
- http://localhost:9999/recipes?title=enchiladas
- http://localhost:9999/recipes?q=korma

## To Add a Recipe
- curl -X POST \
  http://localhost:9999/recipes \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{
    "title": "mario-burritos",
    "description": "Some burrito description",
    "ingredients": [
        "egg",
        "tortilla"
    ],
    "directions": [
        "do something for 5 mins",
        "do something else for 5 mins"
    ]
}'

## To Update a Recipe
- curl -X PATCH \
  http://localhost:9999/recipes \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{"id": "mario-burritos", "title": "new mario burritos", "tags": ["main dish"]}'

## To Delete a Recipe
- curl -X DELETE \
  http://localhost:9999/recipes \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{"id": "mario-burritos"}'

## Uninstall
- UNIX based: run "bash ./install.sh destroy"
