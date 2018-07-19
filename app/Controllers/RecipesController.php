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

        // would be better to bind an interface and resolve automatically
        // don't know how to do it with this framework yet
        $finder = new RecipeFinder;

        $results = $finder->find(
            $this->container['ElasticBuilder'],
            $request->getQueryParams()
        );

        return $response->withJson($results);
    }
}
