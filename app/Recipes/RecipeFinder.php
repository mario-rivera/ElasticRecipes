<?php
namespace App\Recipes;

use \App\Recipes\iRecipeFinder;
use Elasticsearch\Client;
use RecipeSearch\Constants;

class RecipeFinder implements iRecipeFinder{

    public function find(Client $client, $params){
        // Setup search query
        $searchParams['index'] = Constants::ES_INDEX; // which index to search
        $searchParams['type']  = Constants::ES_TYPE;  // which type within the index to search
        $searchParams['size']  = 1000;

        if(isset($params['q'])){

            $searchParams['body']['query']['match']['_all'] = $params['q']; // what to search for
        }

        // Send search query to Elasticsearch and get results
        $queryResponse = $client->search($searchParams);

        return $queryResponse['hits']['hits'];
    }
}
