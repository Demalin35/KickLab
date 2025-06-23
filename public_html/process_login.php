<?php
session_start();
require_once 'config.php';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get login data
$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (empty($email) || empty($password)) {
    $_SESSION['errors'] = ['Email and password are required.'];
    header('Location: login.php');
    exit;
}

// Find user by email
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'first_name' => $user['first_name'],
        'email' => $user['email']
    ];

    $_SESSION['success'] = 'Welcome back, ' . $user['first_name'] . '!';
    header('Location: index.php');
    exit;
} else {
    $_SESSION['errors'] = ['Invalid email or password.'];
    header('Location: login.php');
    exit;
}
