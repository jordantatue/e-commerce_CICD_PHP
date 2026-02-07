<?php

declare(strict_types=1);

function currentUser(): ?array
{
    return $_SESSION['auth_user'] ?? null;
}

function isAuthenticated(): bool
{
    return currentUser() !== null;
}

function requireAuth(): void
{
    if (isAuthenticated()) {
        return;
    }

    flash('error', 'Please login to continue.');
    redirect('/auth/login.php');
}

function loginUser(array $user): void
{
    $_SESSION['auth_user'] = [
        'user_id' => (int) $user['user_id'],
        'email' => (string) $user['email'],
        'full_name' => (string) $user['full_name'],
    ];

    session_regenerate_id(true);
}

function logoutUser(): void
{
    unset($_SESSION['auth_user']);
    session_regenerate_id(true);
}

function attemptLogin(string $email, string $password): bool
{
    $user = findUserByEmail($email);
    if ($user === null) {
        return false;
    }

    $storedPassword = (string) $user['password'];
    $isHash = password_get_info($storedPassword)['algo'] !== null;
    $isValid = $isHash ? password_verify($password, $storedPassword) : hash_equals($storedPassword, $password);

    if (!$isValid) {
        return false;
    }

    loginUser($user);
    return true;
}
