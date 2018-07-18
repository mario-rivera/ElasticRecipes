<?php
namespace App\Recipes;

use App\Recipes\iRecipeAdder;
use Elasticsearch\Client;
use RecipeSearch\Util;
use RecipeSearch\Constants;
use App\Recipes\RecipeExistsException;

class RecipeAdder implements iRecipeAdder{

    public function add(Client $client, $params){

        // Convert recipe title to ID
        $id = Util::recipeTitleToId($params['title']);

        // Check if recipe with this ID already exists
        $exists = $client->exists([
            'id'    => $id,
            'index' => Constants::ES_INDEX,
            'type'  => Constants::ES_TYPE
        ]);

        if($exists){

            throw new RecipeExistsException('A recipe with this title exists already.');
        }

        // Index the recipe in Elasticsearch
        $recipe = $params;
        if(isset($params['tags'])){

            $recipe['tags'] = Util::recipeTagsToArray($params['tags']);
        }
        
        $document = [
            'id'    => $id,
            'index' => Constants::ES_INDEX,
            'type'  => Constants::ES_TYPE,
            'body'  => $recipe
        ];

        return $client->index($document);
    }
}
