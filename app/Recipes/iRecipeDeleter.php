<?php
namespace App\Recipes;
use Elasticsearch\Client;

interface iRecipeDeleter
{
    public function delete(Client $client, $params);
}
