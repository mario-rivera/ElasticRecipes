<?php
namespace App\Recipes;

use App\Recipes\iRecipeUpdater;
use Elasticsearch\Client;
use RecipeSearch\Util;
use RecipeSearch\Constants;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException as ElasticInvalidArgumentException;

use App\Recipes\RecipeNotFoundException;
use App\Recipes\InvalidArgumentsException;

class RecipeUpdater implements iRecipeUpdater{

    public function update(Client $client, $params){

        $id = $params['id'];
        $doc = $this->mapParamsToDoc($params);

        $params = [
            'index' => Constants::ES_INDEX,
            'type' => Constants::ES_TYPE,
            'id' => $id,
            'body' => [
                'doc' => $doc
            ]
        ];

        try{

            $response = $client->update($params);
        }catch(Missing404Exception $e){

            throw new RecipeNotFoundException("The recipe you requested was not found.");
        } catch(ElasticInvalidArgumentException $e){

            throw new InvalidArgumentsException($e->getMessage());
        }

        return $response;
    }

    private function mapParamsToDoc($params){

        $doc = [];

        unset($params['id']);
        foreach ($params as $key => $value){

            $doc[$key] = $value;
        }

        return $doc;
    }
}
