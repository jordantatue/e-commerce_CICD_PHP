<?php

declare(strict_types=1);

function findUserByEmail(string $email): ?array
{
    $statement = db()->prepare('SELECT user_id, full_name, email, password FROM users WHERE email = :email LIMIT 1');
    $statement->execute(['email' => $email]);
    $user = $statement->fetch();

    return $user === false ? null : $user;
}

function getEnabledRecipes(): array
{
    $statement = db()->query(
        'SELECT r.recipe_id, r.title, r.recipe, r.author, '
        . 'COALESCE(ROUND(AVG(c.review), 1), 0) AS avg_rating, '
        . 'COUNT(c.comment_id) AS comment_count '
        . 'FROM recipes r '
        . 'LEFT JOIN comments c ON c.recipe_id = r.recipe_id '
        . 'WHERE r.is_enabled = 1 '
        . 'GROUP BY r.recipe_id, r.title, r.recipe, r.author '
        . 'ORDER BY r.recipe_id DESC'
    );

    return $statement->fetchAll();
}

function getRecipeById(int $recipeId): ?array
{
    $statement = db()->prepare(
        'SELECT r.recipe_id, r.title, r.recipe, r.author, '
        . 'COALESCE(ROUND(AVG(c.review), 1), 0) AS avg_rating, '
        . 'COUNT(c.comment_id) AS comment_count '
        . 'FROM recipes r '
        . 'LEFT JOIN comments c ON c.recipe_id = r.recipe_id '
        . 'WHERE r.recipe_id = :recipe_id AND r.is_enabled = 1 '
        . 'GROUP BY r.recipe_id, r.title, r.recipe, r.author '
        . 'LIMIT 1'
    );
    $statement->execute(['recipe_id' => $recipeId]);

    $recipe = $statement->fetch();
    return $recipe === false ? null : $recipe;
}

function getCommentsByRecipeId(int $recipeId): array
{
    $statement = db()->prepare(
        'SELECT c.comment_id, c.comment, c.review, DATE_FORMAT(c.created_at, "%d/%m/%Y") AS created_at, '
        . 'u.full_name '
        . 'FROM comments c '
        . 'INNER JOIN users u ON u.user_id = c.user_id '
        . 'WHERE c.recipe_id = :recipe_id '
        . 'ORDER BY c.created_at DESC, c.comment_id DESC'
    );
    $statement->execute(['recipe_id' => $recipeId]);

    return $statement->fetchAll();
}

function createRecipe(string $title, string $recipe, string $author): int
{
    $statement = db()->prepare(
        'INSERT INTO recipes (title, recipe, author, is_enabled) '
        . 'VALUES (:title, :recipe, :author, 1)'
    );
    $statement->execute([
        'title' => $title,
        'recipe' => $recipe,
        'author' => $author,
    ]);

    return (int) db()->lastInsertId();
}

function updateRecipe(int $recipeId, string $title, string $recipe): bool
{
    $statement = db()->prepare(
        'UPDATE recipes SET title = :title, recipe = :recipe WHERE recipe_id = :recipe_id AND is_enabled = 1'
    );

    $statement->execute([
        'title' => $title,
        'recipe' => $recipe,
        'recipe_id' => $recipeId,
    ]);

    return $statement->rowCount() > 0;
}

function deleteRecipe(int $recipeId): bool
{
    $statement = db()->prepare('DELETE FROM recipes WHERE recipe_id = :recipe_id');
    $statement->execute(['recipe_id' => $recipeId]);

    return $statement->rowCount() > 0;
}

function addComment(int $recipeId, int $userId, int $review, string $comment): void
{
    $statement = db()->prepare(
        'INSERT INTO comments (comment, recipe_id, user_id, review) '
        . 'VALUES (:comment, :recipe_id, :user_id, :review)'
    );
    $statement->execute([
        'comment' => $comment,
        'recipe_id' => $recipeId,
        'user_id' => $userId,
        'review' => $review,
    ]);
}
