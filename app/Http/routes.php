<?php

$app->get('/', \App\Controllers\RecipesController::class . ':getHome');
