<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

function showMessage(string $title, string $message): void
{
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    echo "<!doctype html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>{$safeTitle}</title><link rel='stylesheet' href='style.css'></head><body><main class='auth-container' style='color:#eee;text-align:center'><h2>{$safeTitle}</h2><p>{$safeMessage}</p><p style='margin-top:24px'><a style='color:#9ec8e8' href='index.html'>Go to Login</a></p></main></body></html>";
}

try {
    $stmt = $conn->prepare('SELECT fullname, password FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        showMessage('Login Successful!', 'Welcome back, ' . $user['fullname'] . '!');
    } else {
        http_response_code(401);
        showMessage('Invalid Email or Password', 'Please check your login details and try again.');
    }
} catch (PDOException $e) {
    error_log('Login failed: ' . $e->getMessage());
    http_response_code(500);
    showMessage('Login failed', 'Something went wrong. Please try again later.');
}
