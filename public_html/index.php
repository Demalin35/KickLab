<?php
session_start();
include 'nitropack-config.php';
require_once 'config.php';




// Connect to the database 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Wishlist functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = intval($_POST['product_id'] ?? 0);

    if ($productId <= 0) {
        echo 'Invalid product ID';
        exit;
    }

    if ($action === 'add') {
        $_SESSION['wishlist'][$productId] = true;
        echo 'Product added to wishlist';
    } elseif ($action === 'remove') {
        unset($_SESSION['wishlist'][$productId]);
        echo 'Product removed from wishlist';
    } else {
        echo 'Invalid action';
    }
    exit;
}

// Popular products
try {
    $stmt = $pdo->query("
    SELECT id, brand, name, price, old_price, image_main, image_hover, color 
    FROM products 
    WHERE popular = 1 
    ORDER BY id DESC 
    LIMIT 5
");
    $popularProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch popular products: " . $e->getMessage());
}

// Lastly Added Products
try {
    $stmt = $pdo->query("
        SELECT id, brand, name, price, old_price, image_main, image_hover, color 
        FROM products 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $lastlyAddedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch lastly added products: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KickLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        .category-card {
            transition: transform 0.3s ease;
        }

        .category-card:hover {
            transform: scale(1.03);
        }

        .promo-banner,
        .navbar,
        .footer {
            padding: 10px;
        }

        @media (max-width: 576px) {

            .promo-banner,
            .navbar,
            .footer {
                padding: 5px;
            }

            .product-card,
            .category-card {
                margin-bottom: 1rem;
            }

            .category-section {
                flex-direction: column;
                gap: 10px;
            }

            .category-section .nav-link {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <!-- Top Banner -->
    <div class="promo-banner">
        MINI HOLIDAY! EXTRA -20% on spring products with code in cart |
        <a href="#">WOMEN</a> | <a href="#">MEN</a> | <a href="#">KIDS</a>
    </div>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Promo -->
    <div class="container mt-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>MINI HOLIDAY</h2>
                <h3>EXTRA -20%</h3>
                <p>Use the code: <code>20HOLS</code></p>
                <a href="#" class="btn btn-dark">CHECK IT OUT</a>
                <p class="mt-2">for spent min. 169 USD for selected products until 04.05.</p>
            </div>
            <div class="col-md-6">
                <img src="assets/hero-shoes.jpg" class="img-fluid hero-rotate" alt="Promo Shoes">
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="container my-5">
        <h4 class="mb-3">Popular Products</h4>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
            <?php foreach ($popularProducts as $product): ?>
                <div class="col">
                    <div class="card product-card p-2 h-100 position-relative overflow-hidden">
                        <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>"
                            class="text-decoration-none text-dark">
                            <div class="product-image-wrapper position-relative">
                                <img src="<?= htmlspecialchars($product['image_main']) ?>" class="card-img-top main-img"
                                    alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php if (!empty($product['image_hover'])): ?>
                                    <img src="<?= htmlspecialchars($product['image_hover']) ?>" class="card-img-top hover-img"
                                        alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php endif; ?>
                                <!-- Wishlist Icon -->
                                <div class="wishlist-icon position-absolute top-0 end-0 p-2">
                                    <i class="bi bi-heart" data-product-id="<?= $product['id'] ?>"
                                        style="font-size: 24px; cursor: pointer;"></i>
                                </div>
                            </div>
                        </a>
                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($product['brand']) ?></h6>
                            <p class="small text-muted mb-1"><?= htmlspecialchars($product['name']) ?></p>
                            <p class="text-danger fw-bold mb-0"><?= number_format($product['price'], 2) ?> USD.</p>
                            <?php if (!empty($product['old_price']) && $product['old_price'] > $product['price']): ?>
                                <p class="small text-muted">Old price <?= number_format($product['old_price'], 2) ?> USD.</p>
                            <?php endif; ?>
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
    </div>

    <!-- Brands -->
    <div class="container text-center my-5">
        <h4>Top brands at attractive prices</h4>
        <div class="d-flex flex-wrap justify-content-center">
            <img src="assets/reebok-logo.png" class="brand-logo" alt="Reebok">
            <img src="assets/guess-logo.png" class="brand-logo" alt="Guess">
            <img src="assets/karl-logo.png" class="brand-logo" alt="Karl">
            <img src="assets/adidas-logo.png" class="brand-logo" alt="Skechers">
        </div>
    </div>

    <!-- New Collection Banner -->
    <div class="container my-5">
        <div class="row align-items-center new-collection-banner p-4 rounded">
            <div class="col-md-6">
                <h2>NEW COLLECTION<br>SS25</h2>
                <a href="#" class="btn btn-dark mt-3">DISCOVER THE COLLECTION</a>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
                    <img src="assets/kappa1.png" class="img-fluid rounded" alt="Kappa Model" style="max-width: 250px;">
                    <img src="assets/kappa2.png" class="img-fluid rounded" alt="Kappa Shoes" style="max-width: 250px;">
                </div>
            </div>
        </div>
    </div>


    <!-- Lastly Added Section -->
    <div class="container my-5">
        <h4 class="mb-3">Lastly Added</h4>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
            <?php foreach ($lastlyAddedProducts as $product): ?>
                <div class="col">
                    <div class="card product-card p-2 h-100 position-relative overflow-hidden">
                        <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>"
                            class="text-decoration-none text-dark">
                            <div class="product-image-wrapper position-relative">
                                <img src="<?= htmlspecialchars($product['image_main']) ?>" class="card-img-top main-img"
                                    alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php if (!empty($product['image_hover'])): ?>
                                    <img src="<?= htmlspecialchars($product['image_hover']) ?>" class="card-img-top hover-img"
                                        alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php endif; ?>
                                <div class="wishlist-icon position-absolute top-0 end-0 p-2">
                                    <i class="bi bi-heart" data-product-id="<?= $product['id'] ?>"
                                        style="font-size: 24px; cursor: pointer;"></i>
                                </div>
                            </div>
                        </a>
                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($product['brand']) ?></h6>
                            <p class="small text-muted mb-1"><?= htmlspecialchars($product['name']) ?></p>
                            <p class="text-danger fw-bold mb-0"><?= number_format($product['price'], 2) ?> USD.</p>
                            <?php if (!empty($product['old_price']) && $product['old_price'] > $product['price']): ?>
                                <p class="small text-muted">Old price <?= number_format($product['old_price'], 2) ?> USD.</p>
                            <?php endif; ?>
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
    </div>


    <!-- Choose a Size Section -->

    <div class="container my-5">
        <div class="row align-items-center bg-light p-4 rounded">
            <!-- Left side: Size slider -->
            <div class="col-md-6">
                <h2 class="mb-3">Choose your size</h2>
                <input type="range" id="sizeSlider" min="0" max="16" step="1" value="0" class="form-range mb-3">
                <div class="mb-3">
                    <span id="selectedSize" class="fw-bold fs-4">37</span>
                </div>
                <a id="viewModelsLink" href="product-list-view.php?size=37" class="btn btn-dark">
                    SEE ALL MODELS WITH SIZE <span id="buttonSize">37</span>
                </a>
            </div>

            <!-- Right side: Shoe image -->
            <div class="col-md-6 text-center">
                <img src="assets/floating.png" id="shoeImage" class="img-fluid rounded" alt="Shoe Image"
                    style="max-width: 700px;">
            </div>
        </div>
    </div>



    <!-- On Sale -->
    <div class="container my-5">
        <div class="row align-items-center bg-light p-4 rounded">
            <div class="col-md-6">
                <h2>Don't miss out! Now at a discount<br>SS25</h2>
                <a href="#" class="btn btn-dark mt-3">GRAB THE OFFER</a>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
                    <img src="assets/runner-wearing.jpg" class="img-fluid rounded" alt="running shoes"
                        style="max-width: 250px;">
                    <img src="assets/close-up-hands.jpg" class="img-fluid rounded" alt="running shoes 2"
                        style="max-width: 250px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Selling Points Section -->
    <div class="py-5" style="background-color: #f1f1f1;">
        <div class="container text-center">
            <h4 class="mb-4">The best offer on the market</h4>
            <div class="row g-3 justify-content-center">
                <div class="col-md-2 col-6">
                    <div class="bg-white p-3 h-100 shadow-sm">
                        <img src="assets/delivery-icon.png" class="mb-2" height="40" alt="Delivery">
                        <p class="mb-0">Fast delivery</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="bg-success text-white p-3 h-100 shadow-sm">
                        <img src="assets/return-icon.png" class="mb-2" height="40" alt="Return">
                        <p class="mb-0">30 days return period</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="bg-white p-3 h-100 shadow-sm">
                        <img src="assets/payment-icon.png" class="mb-2" height="40" alt="Payment">
                        <p class="mb-0">Convenient and safe payments</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="bg-success text-white p-3 h-100 shadow-sm">
                        <img src="assets/tag-icon.png" class="mb-2" height="40" alt="Brands">
                        <p class="mb-0">Hundreds of brands</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="bg-white p-3 h-100 shadow-sm">
                        <h3>100%</h3>
                        <p class="mb-0">Guarantee for original products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const wishlistIcons = document.querySelectorAll(".wishlist-icon i");

            wishlistIcons.forEach(icon => {
                icon.addEventListener("click", function (event) {
                    event.preventDefault();
                    const productId = this.getAttribute("data-product-id");
                    const isActive = this.classList.contains("active");
                    const action = isActive ? "remove" : "add";

                    // Toggle icon classes
                    this.classList.toggle("active");
                    this.classList.toggle("bi-heart-fill");
                    this.classList.toggle("bi-heart");
                    this.classList.toggle("text-danger");

                    // AJAX request to index.php itself
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        }
                    };
                    xhr.send("action=" + action + "&product_id=" + productId);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const sizeSlider = document.getElementById('sizeSlider');
            const selectedSize = document.getElementById('selectedSize');
            const buttonSize = document.getElementById('buttonSize');
            const viewModelsLink = document.getElementById('viewModelsLink');

            const sizes = [
                "37", "38 1/3", "39", "39 1/3", "40", "40 2/3",
                "41 1/3", "42", "42 2/3", "43 1/3", "44", "44 2/3",
                "45 1/3", "46", "46 2/3", "47 1/3", "48"
            ];

            sizeSlider.addEventListener('input', function () {
                const index = parseInt(this.value);
                const size = sizes[index];
                selectedSize.textContent = size;
                buttonSize.textContent = size;
                viewModelsLink.href = `product-list-view.php?size=${encodeURIComponent(size)}`;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const isHomepage = window.location.pathname.includes("index.php") || window.location.pathname === "/";

            const popupShown = localStorage.getItem("newsletter_shown");
            const popup = document.getElementById("newsletterPopup");
            const closeBtn = document.getElementById("closeNewsletter");
            const form = document.getElementById("newsletterForm");

            if (isHomepage && !popupShown) {
                setTimeout(() => {
                    popup.style.display = "flex";
                }, 1500);
            }

            closeBtn.addEventListener("click", function () {
                popup.style.display = "none";
                localStorage.setItem("newsletter_shown", "true");
            });

            form.addEventListener("submit", function (e) {
                e.preventDefault();
                alert("Thanks for subscribing!");
                popup.style.display = "none";
                localStorage.setItem("newsletter_shown", "true");
            });
        });

    </script>

    <!-- Newsletter Popup -->
    <div id="newsletterPopup" class="newsletter-modal">
        <div class="newsletter-content">
            <button id="closeNewsletter" class="close-btn">&times;</button>
            <h2>Join Our Newsletter</h2>
            <p>Be the first to know about new arrivals, sales, and more.</p>
            <form id="newsletterForm">
                <input type="email" name="email" required placeholder="Enter your email" class="form-control mb-2">
                <button type="submit" class="btn btn-dark w-100">Subscribe</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>