<?php
session_start();

if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success text-center">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php
define("NITROPACK_HOME_URL", "dodgerblue-loris-317943.hostingersite.com");
define("NITROPACK_SITE_ID", "ClBFIUiICYPgzifeGhlaVuVWbXJSpRac");
define("NITROPACK_SITE_SECRET", "HLvp1udUikpktvxsI5ofUsEMY2KmLFmz863o46cPySycUq9V8R3DzQoaaUAxnfky");
include_once $_SERVER['DOCUMENT_ROOT'] . "/third_party/nitropack-sdk/bootstrap.php";
?>

<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Obuvki Clone</title>
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
                <img src="assets/hero-shoes.jpg" class="img-fluid" alt="Promo Shoes">
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="container my-5">
        <h4 class="mb-3">Popular Products</h4>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
            <?php for ($i = 0; $i < 5; $i++): ?>
                <div class="col">
                    <a href="product.php?id=<?php echo $i; ?>" class="text-decoration-none">
                        <div class="card product-card">
                            <img src="assets/shoe<?php echo $i + 1; ?>.jpg" class="card-img-top"
                                alt="Shoe <?php echo $i + 1; ?>">
                            <div class="card-body">
                                <h6 class="card-title">Reebok</h6>
                                <p class="card-text">Sneakers Club C Revenge</p>
                                <strong>149,99 USD.</strong>
                            </div>
                            <div class="wishlist-icon position-absolute top-0 end-0 p-2">
                                <i class="bi bi-heart" data-product-id="<?php echo $i; ?>"
                                    style="font-size: 24px; cursor: pointer;"></i>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endfor; ?>
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
        <div class="row align-items-center bg-light p-4 rounded">
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

    <!-- New Section - Categories -->
    <div class="container my-5">
        <h4 class="mb-3">Inspire Yourself from the Spring Hits</h4>
        <div class="d-flex justify-content-center mb-4">
            <a href="women.php" class="me-3 text-dark fw-bold">Women</a>
            <a href="men.php" class="me-3 text-dark">Men</a>
            <a href="kids.php" class="text-dark">Kids</a>
        </div>
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
            <?php
            $categories = [
                ['id' => 1, 'name' => 'Asics', 'image' => 'assets/asics.webp', 'link' => 'category.php?id=1'],
                ['id' => 2, 'name' => 'Calvin Klein', 'image' => 'assets/calvinklein.webp', 'link' => 'category.php?id=2'],
                ['id' => 3, 'name' => 'Tommy Hilfiger', 'image' => 'assets/tommyhilfiger.webp', 'link' => 'category.php?id=3'],
                ['id' => 4, 'name' => 'New Balance', 'image' => 'assets/snickers.webp', 'link' => 'category.php?id=4'],
                ['id' => 5, 'name' => 'Skechers', 'image' => 'assets/skechers.webp', 'link' => 'category.php?id=5'],
                ['id' => 6, 'name' => 'Quicksilver', 'image' => 'assets/quicksilver.webp', 'link' => 'category.php?id=6'],
                ['id' => 7, 'name' => 'Loafers', 'image' => 'assets/loafers.webp', 'link' => 'category.php?id=7'],
                ['id' => 8, 'name' => 'HUGO', 'image' => 'assets/hugo.webp', 'link' => 'category.php?id=8'],
                ['id' => 9, 'name' => 'Calvin Klein', 'image' => 'assets/covers.webp', 'link' => 'category.php?id=9'],
                ['id' => 10, 'name' => 'Converse', 'image' => 'assets/converse.webp', 'link' => 'category.php?id=10'],
            ];

            foreach ($categories as $category): ?>
                <div class="col">
                    <a href="<?php echo $category['link']; ?>" class="text-decoration-none">
                        <div class="card category-card">
                            <img src="<?php echo $category['image']; ?>" class="card-img-top"
                                alt="<?php echo $category['name']; ?>">
                            <div class="card-body text-center">
                                <h6 class="card-title"><?php echo $category['name']; ?></h6>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
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
                icon.addEventListener("click", function () {
                    const productId = this.getAttribute("data-product-id");
                    const isActive = this.classList.contains("bi-heart-fill");
                    const action = isActive ? "remove" : "add";
                    // Toggle icon class

                    this.classList.toggle("bi-heart-fill");
                    this.classList.toggle("text-danger");
                    this.classList.toggle("bi-heart");


                    // AJAX request to update wishlist
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "wishlist-handler.php", true);
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
    </script>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

</body>

</html>