<?php
namespace App\ServiceProviders;
use Slim\App as Slim;

class RouteService {

    public function register(Slim $app){

        $container = $app->getContainer();
        require_once $container['dir.base'] . '/app/Http/routes.php';
    }
}
