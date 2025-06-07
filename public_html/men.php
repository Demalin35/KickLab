<?php
session_start();

// DB config
$host = 'localhost';
$db = 'u638680811_ecommerce';
$user = 'u638680811_Alina';
$pass = '2012Dtlm!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Base query
$query = "SELECT * FROM products WHERE gender = 'men'";
$params = [];

// Filter by brand
if (!empty($_GET['brand'])) {
    $query .= " AND brand = :brand";
    $params[':brand'] = $_GET['brand'];
}

// Filter by price
if (!empty($_GET['price'])) {
    if ($_GET['price'] === 'low') {
        $query .= " AND price < 150";
    } elseif ($_GET['price'] === 'mid') {
        $query .= " AND price BETWEEN 150 AND 250";
    } elseif ($_GET['price'] === 'high') {
        $query .= " AND price > 250";
    }
}

// Filter by color
if (!empty($_GET['color'])) {
    $query .= " AND color = :color";
    $params[':color'] = $_GET['color'];
}

// Filter by brand
if (!empty($_GET['brand'])) {
    $query .= " AND brand = :brand";
    $params[':brand'] = $_GET['brand'];
}

// Filter by size
if (!empty($_GET['size'])) {
    $query .= " AND size = :size";
    $params[':size'] = $_GET['size'];
}

// Filter by product type
if (!empty($_GET['type'])) {
    $query .= " AND type = :type";
    $params[':type'] = $_GET['type'];
}

if (!empty($_GET['category'])) {
    $query .= " AND category = :category";
    $params[':category'] = $_GET['category'];
}

// Sort by
$orderBy = '';
if (!empty($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'price-asc':
            $orderBy = 'ORDER BY price ASC';
            break;
        case 'price-desc':
            $orderBy = 'ORDER BY price DESC';
            break;
        case 'newest':
            $orderBy = 'ORDER BY created_at DESC';
            break;
        case 'discount':
            $orderBy = 'ORDER BY (old_price - price) DESC';
            break;
    }
}

$query .= " $orderBy";

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Count total products
$countStmt = $pdo->prepare(str_replace('SELECT *', 'SELECT COUNT(*)', $query));
$countStmt->execute($params);
$totalProducts = $countStmt->fetchColumn();
$totalPages = ceil($totalProducts / $limit);

// Append LIMIT + OFFSET
$query .= " LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);

// Re-bind filters + pagination
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <title>Men's shoes | KickLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="assets/styles.css" rel="stylesheet">
</head>


<body>

    <div class="promo-banner">
        MINI HOLIDAY! EXTRA -20% on spring products with code in cart |
        <a href="#">WOMEN</a>
        <a href="#">MEN</a>
        <a href="#">KIDS</a>
    </div>

    <?php include 'navbar.php'; ?>

    <div class="container-fluid my-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 ps-4">
                <h6 class="fw-bold">Product Types</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="?category=asphalt" class="text-decoration-none text-dark">
                            Sneakers for asphalt <span class="badge bg-light text-dark">595</span>
                        </a>
                    </li>
                    <li>
                        <a href="?category=mountain" class="text-decoration-none text-dark">
                            Sneakers for mountain running <span class="badge bg-light text-dark">331</span>
                        </a>
                    </li>
                    <li>
                        <a href="?category=daily" class="text-decoration-none text-dark">
                            Sneakers for everyday <span class="badge bg-light text-dark">228</span>
                        </a>
                    </li>
                    <li>
                        <a href="?category=running" class="text-decoration-none text-dark">
                            Sneakers for running <span class="badge bg-light text-dark">165</span>
                        </a>
                    </li>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <!-- Filter Bar -->
                <div class="sticky-filter-wrapper bg-white border-bottom py-3">
                    <div class="d-flex flex-wrap gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                Sort by
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?sort=price-asc">Price: Low to High</a></li>
                                <li><a class="dropdown-item" href="?sort=price-desc">Price: High to Low</a></li>
                                <li><a class="dropdown-item" href="?sort=newest">Newest Arrivals</a></li>
                                <li><a class="dropdown-item" href="?sort=discount">Biggest Discount</a></li>
                            </ul>
                        </div>

                        <a href="?price=low" class="btn btn-outline-secondary">Under $150</a>
                        <a href="?price=mid" class="btn btn-outline-secondary">150–250</a>
                        <a href="?price=high" class="btn btn-outline-secondary">250+</a>
                        <!-- Size Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sizeDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Size
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sizeDropdown">
                                <?php
                                $sizes = ['35', '35.5', '36', '36.5', '37', '37.5', '38', '38.5', '39', '39.5', '40', '40.5', '41', '41.5', '42', '42.5', '43', '43.5', '44', '44.5', '45', '45.5', '46'];
                                foreach ($sizes as $size) {
                                    $active = (isset($_GET['size']) && $_GET['size'] == $size) ? 'active' : '';
                                    $query = http_build_query(array_merge($_GET, ['size' => $size]));
                                    echo "<li><a class='dropdown-item $active' href='?$query'>EU $size</a></li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Brand Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="brandDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Brands
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="brandDropdown">
                                <?php
                                $brands = [
                                    'Adidas',
                                    'New Balance',
                                    'Reebok',
                                    'Puma',
                                    'Tommy Hilfiger',
                                    'Calvin Klein',
                                    'Skechers',
                                    'Mizuno',
                                    'Steve Madden',
                                    'Tamaris'
                                ];
                                foreach ($brands as $brand) {
                                    $active = (isset($_GET['brand']) && $_GET['brand'] === $brand) ? 'active' : '';
                                    $query = http_build_query(array_merge($_GET, ['brand' => $brand]));
                                    echo "<li><a class='dropdown-item $active' href='?$query'>" . htmlspecialchars($brand) . "</a></li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Colour
                            </button>
                            <ul class="dropdown-menu">
                                <?php
                                $colors = ['black', 'white', 'blue', 'red', 'pink', 'purple', 'green', 'beige'];
                                foreach ($colors as $color) {
                                    $active = (isset($_GET['color']) && $_GET['color'] === $color) ? 'active' : '';
                                    echo "<li><a class='dropdown-item $active' href='?" . http_build_query(array_merge($_GET, ['color' => $color])) . "'>" . ucfirst($color) . "</a></li>";
                                }
                                ?>
                            </ul>
                        </div>



                        <!-- Product Type Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="typeDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Product type
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="typeDropdown">
                                <?php
                                $types = ['daily', 'running', 'trail', 'training', 'sneakers', 'lifestyle'];
                                foreach ($types as $type) {
                                    $active = (isset($_GET['type']) && $_GET['type'] === $type) ? 'active' : '';
                                    $query = http_build_query(array_merge($_GET, ['type' => $type]));
                                    echo "<li><a class='dropdown-item $active' href='?$query'>" . ucfirst($type) . "</a></li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <a href="men.php" class="btn btn-outline-success">🔧 Clear filters</a>
                    </div>
                </div>

                <style>
                    /* Style dropdown items with hover effect */
                    .dropdown-menu .dropdown-item:hover {
                        background-color: #d8edd5;
                        color: #000;
                        /* black text for good contrast */
                    }

                    /* Optional: style active item for consistency */
                    .dropdown-menu .dropdown-item.active,
                    .dropdown-menu .dropdown-item:active {
                        background-color: #d8edd5;
                        color: #000;
                    }
                </style>

                <!-- Product Grid -->
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card product-card p-2 h-100 position-relative overflow-hidden">
                                <div class="product-image-wrapper position-relative">
                                    <img src="<?= htmlspecialchars($product['image_main']) ?>" class="card-img-top main-img"
                                        alt="<?= htmlspecialchars($product['name']) ?>">

                                    <img src="<?= htmlspecialchars($product['image_hover']) ?>"
                                        class="card-img-top hover-img" alt="<?= htmlspecialchars($product['name']) ?>">

                                    <span
                                        class="position-absolute top-0 end-0 bg-white text-danger px-2 py-1 small border border-danger">
                                        -<?= round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) ?>%
                                    </span>

                                    <div class="hover-actions position-absolute bottom-0 start-0 w-100 text-center pb-3">
                                        <button class="btn btn-sm btn-dark me-1">Add to Cart</button>
                                        <button class="btn btn-sm btn-outline-danger">♡ Wishlist</button>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($product['brand']) ?></h6>
                                    <p class="small text-muted mb-1"><?= htmlspecialchars($product['name']) ?></p>
                                    <p class="text-danger fw-bold mb-0"><?= number_format($product['price'], 2) ?> USD.</p>
                                    <p class="small text-muted">Old price <?= number_format($product['old_price'], 2) ?>
                                        USD.</p>

                                    <div class="d-flex justify-content-center gap-1 mt-2">
                                        <div class="rounded-circle"
                                            style="width:12px; height:12px; background:<?= htmlspecialchars($product['color']) ?>;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <!-- Pagination -->
                <nav class="mt-5 d-flex justify-content-center">
                    <ul class="pagination">
                        <?php
                        $queryParams = $_GET;
                        $queryParams['page'] = $page - 1;
                        ?>
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query($queryParams) ?>">Back</a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++):
                            $queryParams['page'] = $i;
                            ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query($queryParams) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php
                        $queryParams['page'] = $page + 1;
                        ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query($queryParams) ?>">Forward</a>
                        </li>
                    </ul>
                </nav>
                <style>
                    .pagination .page-item .page-link {
                        color: #000;
                        /* default text color */
                        background-color: #fff;
                        border: 1px solid #d8edd5;
                        margin: 0 2px;
                    }

                    .pagination .page-item.active .page-link {
                        background-color: #d8edd5;
                        /* your preferred green */
                        color: #fff;
                        /* white text */
                        border-color: #d8edd5;
                    }

                    .pagination .page-item.disabled .page-link {
                        color: #ccc;
                        background-color: #f8f9fa;
                        border-color: #ddd;
                    }

                    .pagination .page-item .page-link:hover {
                        background-color: #d8edd5;
                        color: #fff;
                        border-color: #d8edd5;
                    }
                </style>


                <!-- Footer Description -->
                <div class="mt-5 px-3">
                    <h5 class="fw-bold">What running shoes to choose? A quick guide</h5>
                    <p class="text-muted">
                        „Running is the key that opens the doors to a better life." Remember, however, that any sport -
                        professional or amateur - requires proper training...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>


</body>

</html>