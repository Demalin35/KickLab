<?php
$host = 'localhost';
$db = 'u638680811_ecommerce';
$user = 'u638680811_Alina';
$pass = '2012Dtlm!';

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = $_POST['email'] ?? '';

    if (!$email) {
        echo json_encode(['exists' => false]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $exists = $stmt->fetch() ? true : false;
    echo json_encode(['exists' => $exists]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'DB error']);
}
