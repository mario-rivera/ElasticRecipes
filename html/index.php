<?php
require_once __DIR__.'/../vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use RecipeSearch\Constants;
use Elasticsearch\ClientBuilder;

$hosts = [
    'elastic-db:' . 9200
];

$client = ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();

// Setup search query
$searchParams['index'] = Constants::ES_INDEX; // which index to search
$searchParams['type']  = Constants::ES_TYPE;  // which type within the index to search
$searchParams['size']  = 1000;  // which type within the index to search

if(isset($_REQUEST['q'])){

    $searchParams['body']['query']['match']['_all'] = $_REQUEST['q']; // what to search for
}

// Send search query to Elasticsearch and get results
$queryResponse = $client->search($searchParams);
$results = $queryResponse['hits']['hits'];

$app = new \Slim\App;
$app->get('/', function (Request $request, Response $response) use($results) {

    $response->getBody()->write(json_encode($results));
    return $response;
});
$app->run();
