<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController{

    public function getHome(Request $request, Response $response){

        return $response->withRedirect('/recipes');
    }
}
