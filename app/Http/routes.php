<?php

$app->get('/recipes', \App\Controllers\RecipesController::class . ':getHome');
$app->post('/recipes', \App\Controllers\AddRecipesController::class . ':postAdd');
$app->delete('/recipes', \App\Controllers\DeleteRecipesController::class . ':deleteRecipe');
$app->patch('/recipes', \App\Controllers\UpdateRecipesController::class . ':patchRecipe');
