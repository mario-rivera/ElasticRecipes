<?php
namespace App\Recipes;
use Elasticsearch\Client;

interface iRecipeFinder
{
    public function find(Client $client, $params);
}
