<?php
namespace App\Recipes;
use Elasticsearch\Client;

interface iRecipeUpdater
{
    public function update(Client $client, $params);
}
