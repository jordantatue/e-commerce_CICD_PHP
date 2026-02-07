<?php

declare(strict_types=1);

function validateLoginInput(array $postData): array
{
    $email = trim((string) ($postData['email'] ?? ''));
    $password = (string) ($postData['password'] ?? '');
    $errors = [];

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    return [
        'email' => $email,
        'password' => $password,
        'errors' => $errors,
    ];
}

function validateRecipeInput(array $postData): array
{
    $title = trim((string) ($postData['title'] ?? ''));
    $recipe = trim((string) ($postData['recipe'] ?? ''));
    $errors = [];

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if ($recipe === '') {
        $errors[] = 'Recipe content is required.';
    }

    return [
        'title' => $title,
        'recipe' => $recipe,
        'errors' => $errors,
    ];
}

function validateCommentInput(array $postData): array
{
    $recipeId = readPositiveInt($postData, 'recipe_id');
    $reviewRaw = filter_var($postData['review'] ?? null, FILTER_VALIDATE_INT);
    $review = $reviewRaw === false ? null : (int) $reviewRaw;
    $comment = trim((string) ($postData['comment'] ?? ''));
    $errors = [];

    if ($recipeId === null) {
        $errors[] = 'Recipe id is invalid.';
    }

    if ($review === null || $review < 1 || $review > 5) {
        $errors[] = 'Review must be between 1 and 5.';
    }

    if ($comment === '') {
        $errors[] = 'Comment can not be empty.';
    }

    return [
        'recipe_id' => $recipeId,
        'review' => $review,
        'comment' => $comment,
        'errors' => $errors,
    ];
}

function validateContactInput(array $postData): array
{
    $email = trim((string) ($postData['email'] ?? ''));
    $message = trim((string) ($postData['message'] ?? ''));
    $errors = [];

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if ($message === '') {
        $errors[] = 'Message is required.';
    }

    return [
        'email' => $email,
        'message' => $message,
        'errors' => $errors,
    ];
}
