<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

requireAuth();

if (requestMethod() !== 'POST') {
    redirect('/recipes/create.php');
}

$input = validateRecipeInput($_POST);
rememberOld($_POST, ['title', 'recipe']);

if ($input['errors'] !== []) {
    flash('error', implode(' ', $input['errors']));
    redirect('/recipes/create.php');
}

$user = currentUser();
$recipeId = createRecipe($input['title'], $input['recipe'], (string) $user['email']);

flash('success', 'Recipe created successfully.');
redirect('/recipes/show.php?id=' . $recipeId);
