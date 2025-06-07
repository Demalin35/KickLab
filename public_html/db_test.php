<?php
$host = 'localhost';
$db = 'u638680811_ecommerce';
$user = 'u638680811_Alina';
$pass = '2012Dtlm!'; // Replace with the actual password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
