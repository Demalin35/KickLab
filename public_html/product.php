<?php
session_start();
include 'nitropack-config.php';
require_once 'config.php';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

$product = null;
$products = [];
$size = $_GET['size'] ?? null;

// 🎯 View logic
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => intval($_GET['id'])]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product)
        die("Product not found.");

    // Images
    $product['imagePaths'] = [];
    if (!empty($product['image_main'])) {
        $product['imagePaths'][] = trim($product['image_main']);
    }
    if (!empty($product['image_gallery'])) {
        foreach (explode(',', $product['image_gallery']) as $img) {
            $img = trim($img);
            if ($img !== $product['image_main']) {
                $product['imagePaths'][] = $img;
            }
        }
    }

    // Sizes
    $product['sizes'] = array_map('trim', explode(',', $product['size'] ?? ''));

} elseif (isset($_GET['brand'])) {
    $brand = trim($_GET['brand']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE brand = :brand");
    $stmt->execute([':brand' => $brand]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($size) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE size LIKE :size");
    $stmt->execute([':size' => '%' . $size . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    die("No product or filter specified.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product["name"]) ?> | KickLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
    <style>
        .product-container {
            padding: 2rem 0;
        }

        .thumbnail-slider {
            max-width: 80px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .thumbnail-slider img {
            width: 100%;
            height: auto;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .thumbnail-slider img:hover {
            transform: scale(1.05);
        }

        .main-image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            max-height: 500px;
        }

        .main-image {
            width: 100%;
            max-width: 450px;
            height: auto;
            cursor: pointer;
        }

        .product-details {
            padding: 1rem;
        }

        .product-details h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .size-selection {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .size-box {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .size-box.active,
        .size-box:hover {
            background-color: #4CAF50;
            color: #fff;
        }

        .add-to-cart-btn {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .add-to-cart-btn:hover {
            background-color: #333;
        }

        .modal-dialog img {
            width: 100%;
            height: auto;
        }

        /* Limit modal image size on larger screens */
        @media (min-width: 992px) {
            #imageModal .modal-dialog {
                max-width: 600px;
            }
        }

        .product-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-top: 15px;
        }
    </style>
</head>
<?php if (isset($_GET['id'])): ?>
    <!-- Render full single product view -->
<?php else: ?>
    <!-- Render product grid -->
    <h2 class="text-center my-5">All Products with Size <?= htmlspecialchars($size) ?></h2>
    <div class="container my-5">
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($products as $p): ?>
                <div class="col">
                    <a href="product.php?id=<?= $p['id'] ?>" class="text-decoration-none">
                        <div class="card h-100">
                            <img src="<?= htmlspecialchars($p['image_main']) ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($p['name']) ?>">
                            <div class="card-body">
                                <h6><?= htmlspecialchars($p['brand']) ?></h6>
                                <p><?= htmlspecialchars($p['name']) ?></p>
                                <p class="text-danger fw-bold"><?= number_format($p['price'], 2) ?> USD</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container product-container">
        <div class="row g-4">
            <!-- Thumbnail Slider -->
            <div class="col-md-1 thumbnail-slider">
                <?php foreach ($product["imagePaths"] as $image): ?>
                    <img src="<?= htmlspecialchars(trim($image)) ?>" class="thumbnail"
                        data-image="<?= htmlspecialchars(trim($image)) ?>" alt="Product Thumbnail">
                <?php endforeach; ?>
            </div>

            <!-- Main Image Display -->
            <div class="col-md-6 main-image-container">
                <img src="<?= htmlspecialchars(trim($product["imagePaths"][0])) ?>" id="mainImage" class="main-image"
                    data-bs-toggle="modal" data-bs-target="#imageModal" alt="Main Product Image">
            </div>

            <!-- Product Details -->
            <div class="col-md-5">
                <div class="product-details">
                    <h1><?= htmlspecialchars($product["brand"]) ?> - <?= htmlspecialchars($product["name"]) ?></h1>
                    <p class="text-muted">Color: <?= htmlspecialchars($product["color"]) ?></p>
                    <p class="fw-bold text-danger"><?= number_format($product["price"], 2) ?> USD</p>

                    <form id="addToCartForm" method="POST">
                        <input type="hidden" name="quantity" value="1">
                        <div class="size-selection mt-3">
                            <?php foreach ($product["sizes"] as $size): ?>
                                <label class="size-box">
                                    <input type="radio" name="size" value="<?= htmlspecialchars(trim($size)) ?>"
                                        class="d-none">
                                    <?= htmlspecialchars(trim($size)) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div id="cartMessage" class="text-danger mt-2"><?= $error ?? '' ?></div>
                        <button type="submit" class="add-to-cart-btn mt-4">Add to Cart</button>
                    </form>

                    <div class="product-info mt-3">
                        <p><?= nl2br(htmlspecialchars($product["description"])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" id="modalImage" alt="Enlarged Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information Section -->
    <div class="product-info mt-5">
        <h4>Product Information</h4>
        <div class="row gy-3">
            <div class="col-md-3">
                <h6>Product Details</h6>
                <p><strong>Brand:</strong> <?= htmlspecialchars($product["brand"]) ?></p>
                <p><strong>Color:</strong> <?= htmlspecialchars($product["color"]) ?></p>
                <p><strong>Collection:</strong> Adidas Terrex</p>
                <p><strong>Code:</strong> 0000304036200</p>
            </div>
            <div class="col-md-3">
                <h6>Materials & Care</h6>
                <p><strong>Material:</strong> Textile</p>
                <p><strong>Sole:</strong> EVA, Traxion</p>
            </div>
            <div class="col-md-3">
                <h6>Size & Characteristics</h6>
                <p><strong>Sole Thickness:</strong> 3 cm</p>
            </div>
            <div class="col-md-3">
                <h6>Manufacturer</h6>
                <p><strong>Company:</strong> ADIDAS POLAND SP. Z O.O.</p>
                <p><strong>Location:</strong> Amsterdam, Netherlands</p>
                <p><strong>Contact:</strong> <a href="mailto:support@adidas.com">support@adidas.com</a></p>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section mt-5 p-4 rounded shadow-sm bg-light">
        <h4 class="mb-4">Customer Reviews (3)</h4>
        <div class="d-flex flex-column gap-3">
            <div class="review-item p-3 bg-white rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold">Svetlozar</span>
                    <span class="text-warning">★★★★★</span>
                </div>
                <p class="text-muted mb-0">Great product! Highly recommended.</p>
            </div>
            <div class="review-item p-3 bg-white rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold">Danail</span>
                    <span class="text-warning">★★★★☆</span>
                </div>
                <p class="text-muted mb-0">Good quality, but could be more comfortable.</p>
            </div>
            <div class="review-item p-3 bg-white rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold">Monica</span>
                    <span class="text-warning">★★★★★</span>
                </div>
                <p class="text-muted mb-0">Absolutely love these shoes!</p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.getElementById('mainImage');
            const modalImage = document.getElementById('modalImage');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function () {
                    const newSrc = this.getAttribute('data-image');
                    mainImage.src = newSrc;
                    modalImage.src = newSrc;
                });
            });

            mainImage.addEventListener('click', function () {
                modalImage.src = this.src;
            });

            // Size selection logic
            const sizeBoxes = document.querySelectorAll('.size-box');
            sizeBoxes.forEach(label => {
                label.addEventListener('click', () => {
                    sizeBoxes.forEach(lb => lb.classList.remove('active'));
                    label.classList.add('active');
                    label.querySelector('input').checked = true;
                });
            });
        });
    </script>
</body>

</html>