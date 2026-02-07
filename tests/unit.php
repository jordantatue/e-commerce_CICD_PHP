<?php

declare(strict_types=1);

require_once(__DIR__ . '/../app/bootstrap.php');

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function test(string $name, callable $callback): void
{
    echo "[unit] {$name}... ";
    $callback();
    echo "OK\n";
}

test('h escapes html', function (): void {
    check(h('<b>x</b>') === '&lt;b&gt;x&lt;/b&gt;', 'h should escape html');
});

test('validateLoginInput detects bad data', function (): void {
    $result = validateLoginInput(['email' => 'x', 'password' => '']);
    check(count($result['errors']) === 2, 'login validation should return two errors');
});

test('validateRecipeInput accepts valid payload', function (): void {
    $result = validateRecipeInput(['title' => 'T', 'recipe' => 'R']);
    check($result['errors'] === [], 'recipe validation should pass');
});

test('validateCommentInput bounds review', function (): void {
    $result = validateCommentInput(['recipe_id' => '2', 'review' => '8', 'comment' => 'ok']);
    check(count($result['errors']) === 1, 'comment validation should fail for invalid review');
});

echo "unit ok\n";
