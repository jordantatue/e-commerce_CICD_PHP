<?php

declare(strict_types=1);

function rootPath(string $path = ''): string
{
    $cleanPath = ltrim($path, '/\\');
    if ($cleanPath === '') {
        return BASE_PATH;
    }

    return BASE_PATH . '/' . $cleanPath;
}

function viewPath(string $path): string
{
    return rootPath('app/Views/' . ltrim($path, '/\\'));
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    $target = str_starts_with($path, '/') ? $path : '/' . $path;
    header('Location: ' . $target);
    exit;
}

function flash(string $key, string $message): void
{
    $_SESSION['_flash'][$key] = $message;
}

function consumeFlash(string $key): ?string
{
    if (!isset($_SESSION['_flash'][$key])) {
        return null;
    }

    $message = (string) $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    return $message;
}

function old(string $key, string $fallback = ''): string
{
    if (!isset($_SESSION['_old'][$key])) {
        return $fallback;
    }

    $value = (string) $_SESSION['_old'][$key];
    unset($_SESSION['_old'][$key]);

    return $value;
}

function rememberOld(array $data, array $keys): void
{
    foreach ($keys as $key) {
        $_SESSION['_old'][$key] = isset($data[$key]) ? trim((string) $data[$key]) : '';
    }
}

function requestMethod(): string
{
    return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
}

function truncateText(string $value, int $maxLength = 140): string
{
    if (strlen($value) <= $maxLength) {
        return $value;
    }

    return substr($value, 0, $maxLength - 3) . '...';
}

function readPositiveInt(array $source, string $key): ?int
{
    if (!isset($source[$key])) {
        return null;
    }

    $value = filter_var($source[$key], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($value === false) {
        return null;
    }

    return (int) $value;
}
