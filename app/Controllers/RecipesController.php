<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use App\Recipes\RecipeFinder;

class RecipesController{

    protected $container;

    public function __construct(ContainerInterface $container) {

        $this->container = $container;
    }

    public function getHome(Request $request, Response $response){

        $finder = new RecipeFinder;
        
        $results = $finder->find(
            $this->container['ElasticBuilder'],
            $request->getQueryParams()
        );

        $response->getBody()->write(json_encode($results));
        return $response;
    }
}
