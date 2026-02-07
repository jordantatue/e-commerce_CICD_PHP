<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

requireAuth();

if (requestMethod() !== 'POST') {
    redirect('/recipes/home.php');
}

$recipeId = readPositiveInt($_POST, 'id');
if ($recipeId === null) {
    flash('error', 'Recipe id is invalid.');
    redirect('/recipes/home.php');
}

$deleted = deleteRecipe($recipeId);
if (!$deleted) {
    flash('error', 'Recipe not found.');
    redirect('/recipes/home.php');
}

flash('success', 'Recipe deleted successfully.');
redirect('/recipes/home.php');
