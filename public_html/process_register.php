<?php
session_start();
require_once 'config.php';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get form inputs
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);
$terms = isset($_POST['terms']) ? 1 : 0;

$errors = [];

// Validation
if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors[] = 'All fields are required.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}

if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match.';
}

if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}

if (!$terms) {
    $errors[] = 'You must agree to the Terms and Conditions.';
}

// Email already in use
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $errors[] = 'An account with this email already exists.';
}

// No errors → insert & login
if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $email, $hashed_password]);

    // Login user instantly
    $_SESSION['user'] = [
        'id' => $pdo->lastInsertId(),
        'first_name' => $first_name,
        'email' => $email
    ];

    // Show success message on homepage
    $_SESSION['success'] = 'Welcome, ' . $first_name . '! Your account has been created.';

    header('Location: index.php');
    exit;
} else {
    $_SESSION['errors'] = $errors;
    header('Location: register.php');
    exit;
}
