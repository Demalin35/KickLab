<?php
require_once 'config.php';

session_start();


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT id, name, image_main, image_hover FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$missingImages = [];

foreach ($products as $product) {
    $mainImagePath = __DIR__ . '/' . $product['image_main'];
    $hoverImagePath = __DIR__ . '/' . $product['image_hover'];

    if (!file_exists($mainImagePath)) {
        $missingImages[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'type' => 'Main',
            'path' => $product['image_main']
        ];
    }

    if (!file_exists($hoverImagePath)) {
        $missingImages[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'type' => 'Hover',
            'path' => $product['image_hover']
        ];
    }
}

echo "<h2>Missing Images Report</h2>";
if (empty($missingImages)) {
    echo "<p>✅ All images are present on the server!</p>";
} else {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Product ID</th><th>Product Name</th><th>Image Type</th><th>Missing Path</th></tr>";
    foreach ($missingImages as $missing) {
        echo "<tr>";
        echo "<td>{$missing['id']}</td>";
        echo "<td>{$missing['name']}</td>";
        echo "<td>{$missing['type']}</td>";
        echo "<td>{$missing['path']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>