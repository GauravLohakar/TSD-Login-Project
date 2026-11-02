<?php
// Render supplies DATABASE_URL. Do not hardcode database credentials here.
$databaseUrl = getenv('DATABASE_URL');

if (!$databaseUrl) {
    http_response_code(500);
    exit('Server configuration error: DATABASE_URL is not configured.');
}

$parts = parse_url($databaseUrl);
if ($parts === false || empty($parts['host']) || empty($parts['user']) || !isset($parts['pass']) || empty($parts['path'])) {
    http_response_code(500);
    exit('Server configuration error: DATABASE_URL is invalid.');
}

$host = $parts['host'];
$port = $parts['port'] ?? 5432;
$dbname = rawurldecode(ltrim($parts['path'], '/'));
$username = rawurldecode($parts['user']);
$password = rawurldecode($parts['pass']);

try {
    // Neon requires TLS; sslmode=require is enforced here.
    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check the DATABASE_URL setting in Render.');
}
