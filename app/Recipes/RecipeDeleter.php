<?php
namespace App\Recipes;

use App\Recipes\iRecipeDeleter;
use Elasticsearch\Client;
use RecipeSearch\Util;
use RecipeSearch\Constants;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException as ElasticInvalidArgumentException;
use App\Recipes\RecipeNotFoundException;
use App\Recipes\InvalidArgumentsException;

class RecipeDeleter implements iRecipeDeleter{

    public function delete(Client $client, $params){

        $id = $params['id'];

        $params = [
            'index' => Constants::ES_INDEX,
            'type' => Constants::ES_TYPE,
            'id' => $id
        ];

        try{

            // Delete doc at /my_index/my_type/my_id
            $response = $client->delete($params);
        }catch(Missing404Exception $e){

            throw new RecipeNotFoundException("The recipe you requested was not found.");
        } catch(ElasticInvalidArgumentException $e){

            throw new InvalidArgumentsException($e->getMessage());
        }

        return $response;
    }
}
