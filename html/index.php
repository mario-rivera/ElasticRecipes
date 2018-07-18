<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\ElasticRecipes;

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

(new ElasticRecipes($config))
->load()->run();
