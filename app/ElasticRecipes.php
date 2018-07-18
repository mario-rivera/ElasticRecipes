<?php
namespace App;
use Slim\App as Slim;

class ElasticRecipes extends Slim{

    public function load(){

        $container = $this->getContainer();
        $container['dir.base'] = dirname(__DIR__);

        $this->bootServiceProviders();
        return $this;
    }

    private function bootServiceProviders(){

        $providers =  require_once  __DIR__ . '/ServiceProviders/definitions.php';

        foreach( $providers as $provider ){

            (new $provider)->register($this);
        }
    }
}
