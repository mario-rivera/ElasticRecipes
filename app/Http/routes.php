<?php

$app->get('/', \App\Controllers\HomeController::class . ':getHome');

$app->get('/recipes', \App\Controllers\RecipesController::class . ':getRecipes');
$app->post('/recipes', \App\Controllers\AddRecipesController::class . ':postAdd');
$app->delete('/recipes', \App\Controllers\DeleteRecipesController::class . ':deleteRecipe');
$app->patch('/recipes', \App\Controllers\UpdateRecipesController::class . ':patchRecipe');
