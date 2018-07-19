<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

use App\Recipes\RecipeDeleter;
use App\Recipes\RecipeNotFoundException;
use App\Recipes\InvalidArgumentsException;

class DeleteRecipesController{

    protected $container;

    public function __construct(ContainerInterface $container) {

        $this->container = $container;
    }

    public function deleteRecipe(Request $request, Response $response){

        $deleter = new RecipeDeleter;

        try{

            $deleter->delete(
                $this->container['ElasticBuilder'],
                $request->getParsedBody()
            );
        }catch(RecipeNotFoundException $e){

            return $response->withJson($e->getMessage(), 404);
        }catch(InvalidArgumentsException $e){

            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(true);
    }
}
