<?php
// config.php — database connection for the shop.
//
// Credentials come from environment variables, never from a file that gets
// committed. For local development the defaults below point at a local
// MySQL server; in production, set DB_HOST / DB_NAME / DB_USER / DB_PASS
// in the server environment instead.

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $name = getenv('DB_NAME') ?: 'shop';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';

    $pdo = new PDO(
        "mysql:host={$host};dbname={$name};charset=utf8mb4",
        $user,
        $pass,
        [
            // Throw exceptions instead of failing silently.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Fetch rows as associative arrays by default.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Use real server-side prepared statements, not emulated ones.
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    return $pdo;
}
