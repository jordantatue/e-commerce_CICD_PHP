<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

requireAuth();

if (requestMethod() !== 'POST') {
    redirect('/recipes/home.php');
}

$input = validateCommentInput($_POST);
if ($input['errors'] !== []) {
    flash('error', implode(' ', $input['errors']));
    $recipeId = $input['recipe_id'] ?? 0;
    redirect('/recipes/show.php?id=' . (int) $recipeId);
}

$recipe = getRecipeById((int) $input['recipe_id']);
if ($recipe === null) {
    flash('error', 'Recipe not found.');
    redirect('/recipes/home.php');
}

$user = currentUser();
addComment((int) $input['recipe_id'], (int) $user['user_id'], (int) $input['review'], (string) $input['comment']);

flash('success', 'Comment added successfully.');
redirect('/recipes/show.php?id=' . (int) $input['recipe_id']);
