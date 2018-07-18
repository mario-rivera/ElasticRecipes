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

            // $searchParams['body']['query']['match']['_all'] = $params['q']; // what to search for
            $searchParams['body'] = $this->doSimpleSearch($params);
        }else {

            $searchParams['body'] = $this->doAdvanceSearch($params);
        }

        // Send search query to Elasticsearch and get results
        $queryResponse = $client->search($searchParams);

        return $queryResponse['hits']['hits'];
    }

    private function doSimpleSearch($params){

        $body = [];
        $body['query']['match']['_all'] = $params['q'];

        return $body;
    }

    private function doAdvanceSearch($params){

        $body = [];

        // First, setup full text search bits
        $fullTextClauses = [];
        if ($params['title']) {
          $fullTextClauses[] = [ 'match' => [ 'title' => $params['title'] ] ];
        }

        if ($params['description']) {
          $fullTextClauses[] = [ 'match' => [ 'description' => $params['description'] ] ];
        }

        if ($params['ingredients']) {
          $fullTextClauses[] = [ 'match' => [ 'ingredients' => $params['ingredients'] ] ];
        }

        if ($params['directions']) {
          $fullTextClauses[] = [ 'match' => [ 'directions' => $params['directions'] ] ];
        }

        if ($params['tags']) {
          $tags = Util::recipeTagsToArray($params['tags']);
          $fullTextClauses[] = [ 'terms' => [
            'tags' => $tags,
            'minimum_should_match' => count($tags)
          ] ];
        }

        if (count($fullTextClauses) > 0) {
          $query = [ 'bool' => [ 'must' => $fullTextClauses ] ];
        } else {
          $query = [ 'match_all' => (object) [] ];
        }

        // Then setup exact match bits
        $filterClauses = [];

        if ($params['prep_time_min_low'] || $params['prep_time_min_high']) {
          $rangeFilter = [];
          if ($params['prep_time_min_low']) {
            $rangeFilter['gte'] = (int) $params['prep_time_min_low'];
          }
          if ($params['prep_time_min_high']) {
            $rangeFilter['lte'] = (int) $params['prep_time_min_high'];
          }
          $filterClauses[] = [ 'range' => [ 'prep_time_min' => $rangeFilter ] ];
        }

        if ($params['cook_time_min_low'] || $params['cook_time_min_high']) {
          $rangeFilter = [];
          if ($params['cook_time_min_low']) {
            $rangeFilter['gte'] = (int) $params['cook_time_min_low'];
          }
          if ($params['cook_time_min_high']) {
            $rangeFilter['lte'] = (int) $params['cook_time_min_high'];
          }
          $filterClauses[] = [ 'range' => [ 'cook_time_min' => $rangeFilter ] ];
        }

        if ($params['servings']) {
          $filterClauses[] = [ 'term' => [ 'servings' => $params['servings'] ] ];
        }

        if (count($filterClauses) > 0) {
          $filter = [ 'bool' => [ 'must' => $filterClauses ] ];
        }

        // Build complete search request body
        if (count($filterClauses) > 0) {

          $body = [ 'query' =>
            [ 'filtered' =>
              [ 'query' => $query, 'filter' => $filter ]
            ]
          ];
        } else {
          $body = [ 'query' => $query ];
        }

        return $body;
    }
}
