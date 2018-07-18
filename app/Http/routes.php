<?php

$app->get('/', \App\Controllers\RecipesController::class . ':getHome');
$app->post('/', \App\Controllers\RecipesController::class . ':postAdd');
