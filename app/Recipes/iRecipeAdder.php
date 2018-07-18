<?php
namespace App\Recipes;
use Elasticsearch\Client;

interface iRecipeAdder
{
    public function add(Client $client, $params);
}
