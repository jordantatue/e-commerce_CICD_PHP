<?php

declare(strict_types=1);

require_once(__DIR__ . '/../variables/functions.php');

// ------------------------------------------------------------
// Unit tests (lightweight, no external libraries)
// ------------------------------------------------------------

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function test(string $name, callable $fn): void
{
    echo "[unit] {$name}... ";
    $fn();
    echo "OK\n";
}

test('displayAuthor() known/unknown', function (): void {
    $users = [
        ['email' => 'a@example.com', 'full_name' => 'Alice', 'age' => 30],
        ['email' => 'b@example.com', 'full_name' => 'Bob', 'age' => 42],
    ];
    check(displayAuthor('b@example.com', $users) === 'Bob(42 ans)', 'displayAuthor known user');
    check(displayAuthor('missing@example.com', $users) === 'Auteur inconnu', 'displayAuthor unknown user');
});

test('isValidRecipe()', function (): void {
    check(isValidRecipe(['is_enabled' => 1]) === true, 'isValidRecipe enabled');
    check(isValidRecipe(['is_enabled' => 0]) === false, 'isValidRecipe disabled');
    check(isValidRecipe([]) === false, 'isValidRecipe missing flag');
});

test('getRecipes()', function (): void {
    $recipes = [
        ['recipe_id' => 1, 'is_enabled' => 1],
        ['recipe_id' => 2, 'is_enabled' => 0],
        ['recipe_id' => 3], // missing is_enabled -> false
    ];
    $valid = getRecipes($recipes);
    check(count($valid) === 1, 'getRecipes count');
    check($valid[0]['recipe_id'] === 1, 'getRecipes keeps enabled');
});

echo "unit ok\n";
