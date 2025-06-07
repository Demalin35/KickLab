<?php
session_start();

$products = [
    ['id' => 0, 'name' => 'Nike Air Force 1', 'price' => '149,99 USD', 'image' => 'assets/shoe1.jpg'],
    ['id' => 1, 'name' => 'Adidas Ultraboost', 'price' => '159,99 USD', 'image' => 'assets/shoe2.jpg'],
    ['id' => 2, 'name' => 'Reebok Club C', 'price' => '129,99 USD', 'image' => 'assets/shoe3.jpg'],
    ['id' => 3, 'name' => 'Puma RS-X', 'price' => '139,99 USD', 'image' => 'assets/shoe4.jpg'],
    ['id' => 4, 'name' => 'New Balance 574', 'price' => '119,99 USD', 'image' => 'assets/shoe5.jpg'],
];

$searchQuery = isset($_GET['query']) ? htmlspecialchars(trim($_GET['query'])) : '';

?>

<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <h4 class="mb-3">Search Results for "<?php echo $searchQuery; ?>"</h4>

        <?php
        if ($searchQuery) {
            $results = array_filter($products, function ($product) use ($searchQuery) {
                return stripos($product['name'], $searchQuery) !== false || stripos($product['price'], $searchQuery) !== false;
            });

            if (empty($results)) {
                echo "<p class='text-muted'>No products found for '<strong>{$searchQuery}</strong>'.</p>";
            } else {
                echo '<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">';
                foreach ($results as $product) {
                    echo "<div class='col'>
                        <div class='card'>
                            <img src='{$product['image']}' class='card-img-top' alt='{$product['name']}'>
                            <div class='card-body'>
                                <h6 class='card-title'>{$product['name']}</h6>
                                <p class='card-text'>{$product['price']}</p>
                            </div>
                        </div>
                    </div>";
                }
                echo '</div>';
            }
        } else {
            echo "<p class='text-muted'>Please enter a keyword to search for products.</p>";
        }
        ?>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>