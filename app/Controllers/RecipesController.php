<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use App\Recipes\RecipeFinder;
use App\Recipes\RecipeAdder;
use App\Recipes\RecipeExistsException;

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

    public function postAdd(Request $request, Response $response){

        $adder = new RecipeAdder;

        try{

            $adder->add(
                $this->container['ElasticBuilder'],
                $request->getParsedBody()
            );
        }catch(RecipeExistsException $e){

            return $response->withStatus(400)->getBody()->write(json_encode($e->getMessage()));
        }

        return $response->getBody()->write(json_encode(true));
    }
}
