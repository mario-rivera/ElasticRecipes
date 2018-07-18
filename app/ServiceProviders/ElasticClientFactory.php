<?php

namespace App\ServiceProviders;
use Slim\App as Slim;
use Elasticsearch\ClientBuilder;

class ElasticClientFactory{

    public function register(Slim $app){

        $container = $app->getContainer();

        $container['ElasticBuilder'] = function ($container) {

            $hosts = [
                'elastic-db:' . 9200
            ];

            $client = ClientBuilder::create()
                                ->setHosts($hosts)
                                ->build();

            return $client;
        };
    }
}
