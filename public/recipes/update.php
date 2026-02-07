<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

requireAuth();

if (requestMethod() !== 'POST') {
    redirect('/recipes/home.php');
}

$recipeId = readPositiveInt($_POST, 'id');
$input = validateRecipeInput($_POST);
rememberOld($_POST, ['title', 'recipe']);

if ($recipeId === null) {
    flash('error', 'Recipe id is invalid.');
    redirect('/recipes/home.php');
}

if ($input['errors'] !== []) {
    flash('error', implode(' ', $input['errors']));
    redirect('/recipes/edit.php?id=' . $recipeId);
}

$updated = updateRecipe($recipeId, $input['title'], $input['recipe']);
if (!$updated) {
    flash('error', 'Recipe update failed or recipe not found.');
    redirect('/recipes/home.php');
}

flash('success', 'Recipe updated successfully.');
redirect('/recipes/show.php?id=' . $recipeId);
