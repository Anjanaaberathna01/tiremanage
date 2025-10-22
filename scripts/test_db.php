<?php
// Quick DB connectivity test using project .env values

require __DIR__ . '/../vendor/autoload.php';

// Load .env
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$db   = $_ENV['DB_DATABASE'] ?? '';
$user = $_ENV['DB_USERNAME'] ?? '';
$pass = $_ENV['DB_PASSWORD'] ?? '';

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $db);

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    echo "OK: Connected to MySQL at {$host}:{$port}, DB={$db}\n";
    $row = $pdo->query('SELECT VERSION() AS v')->fetch(PDO::FETCH_ASSOC);
    echo 'MySQL version: ' . ($row['v'] ?? 'unknown') . "\n";
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    exit(1);
}

