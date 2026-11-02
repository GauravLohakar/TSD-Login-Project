<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

$fullname = trim($_POST['fullname'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

function showMessage(string $title, string $message, string $linkText = 'Back to Login'): void
{
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    echo "<!doctype html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>{$safeTitle}</title><link rel='stylesheet' href='style.css'></head><body><main class='auth-container' style='color:#eee;text-align:center'><h2>{$safeTitle}</h2><p>{$safeMessage}</p><p style='margin-top:24px'><a style='color:#9ec8e8' href='index.html'>{$linkText}</a></p></main></body></html>";
}

if ($fullname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
    http_response_code(400);
    showMessage('Please check your details', 'Enter your full name, a valid email address, and a password with at least 8 characters.', 'Try Again');
    exit;
}

try {
    $sql = 'INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)';
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':fullname' => $fullname,
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    showMessage('Registration Successful!', 'Your account has been created. You can now sign in.');
} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        http_response_code(409);
        showMessage('Email already registered!', 'An account with this email already exists.', 'Try Again');
    } else {
        error_log('Registration failed: ' . $e->getMessage());
        http_response_code(500);
        showMessage('Registration failed', 'Something went wrong while creating your account. Please try again.');
    }
}
