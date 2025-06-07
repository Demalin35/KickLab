<?php
session_start();

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

$wishlistItems = $_SESSION['wishlist'];

?>

<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <title>Wishlist</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <h4 class="mb-3">Your Wishlist</h4>

        <?php if (empty($wishlistItems)): ?>
            <p class="text-muted">Your wishlist is empty.</p>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
                <?php foreach ($wishlistItems as $productId): ?>
                    <div class="col">
                        <div class="card product-card position-relative">
                            <img src="assets/shoe<?php echo $productId + 1; ?>.jpg" class="card-img-top"
                                alt="Product <?php echo $productId; ?>">
                            <div class="card-body">
                                <h6 class="card-title">Reebok</h6>
                                <p class="card-text">Sneakers Club C Revenge</p>
                                <strong>149,99 USD.</strong>
                                <div class="mt-2 d-flex gap-2">
                                    <button class="btn btn-danger btn-sm remove-wishlist"
                                        data-product-id="<?php echo $productId; ?>">Remove</button>
                                    <button class="btn btn-success btn-sm add-to-cart"
                                        data-product-id="<?php echo $productId; ?>">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const removeButtons = document.querySelectorAll(".remove-wishlist");
            const addToCartButtons = document.querySelectorAll(".add-to-cart");
            const wishlistCount = document.getElementById("wishlist-count");

            function updateWishlistCount() {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", "wishlist-handler.php?action=count", true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        wishlistCount.textContent = xhr.responseText;
                    }
                };
                xhr.send();
            }

            removeButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const productId = this.getAttribute("data-product-id");

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "wishlist-handler.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            location.reload();
                        }
                    };
                    xhr.send("action=remove&product_id=" + productId);
                });
            });

            addToCartButtons.forEach(button => {
                button.addEventListener("click", function () {
                    alert("Product added to cart. (Logic not implemented yet)");
                });
            });

            updateWishlistCount();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>