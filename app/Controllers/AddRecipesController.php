<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

use App\Recipes\RecipeAdder;
use App\Recipes\RecipeExistsException;

class AddRecipesController{

    protected $container;

    public function __construct(ContainerInterface $container) {

        $this->container = $container;
    }

    public function postAdd(Request $request, Response $response){

        // would be better to bind an interface and resolve automatically
        // don't know how to do it with this framework yet
        $adder = new RecipeAdder;

        try{

            $adder->add(
                $this->container['ElasticBuilder'],
                $request->getParsedBody()
            );
        }catch(RecipeExistsException $e){

            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(true, 201);
    }
}
