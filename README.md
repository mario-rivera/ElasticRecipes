## Prerequsites
- Uses [Docker](https://www.docker.com/products/docker) to deploy the application.
- Install [Docker](https://docs.docker.com/engine/installation)

## Installation Linux or MacOS
- Run the installation script "bash ./install.sh install"
- This will build the docker image and run the containers

## After Installation
- Go to localhost:9999 to view the application.

## Additional Notes
- Project built with Slim

## Uninstall
- UNIX based: run "bash ./install.sh destroy"

## Uninstall
- curl -X POST \
  http://localhost:9999/ \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/x-www-form-urlencoded' \
  -d 'title=mario%20burritos&description=Some%20burrito%20description&ingredients%5B%5D=egg&ingredients%5B%5D=tortilla&directions%5B%5D=do%20something%20for%205%20mins&directions%5B%5D=do%20something%20else%20for%205%20mins'

## To Find a Recipe
- http://localhost:9999?title=enchiladas
- http://localhost:9999?q=chicken
