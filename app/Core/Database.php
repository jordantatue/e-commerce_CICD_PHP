<?php

declare(strict_types=1);

function buildDsn(array $config, string $host): string
{
    return sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $host,
        $config['port'],
        $config['name']
    );
}

function connectionHostCandidates(array $config): array
{
    $configuredHost = trim((string) ($config['host'] ?? ''));
    $insideDocker = file_exists('/.dockerenv');

    $hosts = [];
    if ($configuredHost !== '') {
        $hosts[] = $configuredHost;
    }

    if ($insideDocker) {
        $hosts[] = 'db';
        $hosts[] = 'mysql-container';
        $hosts[] = 'host.docker.internal';
    }

    $hosts[] = '127.0.0.1';
    $hosts[] = 'localhost';

    return array_values(array_unique($hosts));
}

function databaseConfig(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $config = require rootPath('config/database.php');
    return $config;
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = databaseConfig();
    $lastError = null;

    foreach (connectionHostCandidates($config) as $host) {
        try {
            $pdo = new PDO(buildDsn($config, $host), $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3,
            ]);
            return $pdo;
        } catch (PDOException $exception) {
            $lastError = $exception;
        }
    }

    $triedHosts = implode(', ', connectionHostCandidates($config));
    $errorMessage = $lastError instanceof Throwable ? $lastError->getMessage() : 'unknown error';
    throw new RuntimeException(
        'Database connection failed. Tried hosts: ' . $triedHosts . '. Last error: ' . $errorMessage,
        0,
        $lastError instanceof Throwable ? $lastError : null
    );
}
