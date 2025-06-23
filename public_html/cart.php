<?php
session_start();
$cart = $_SESSION["cart"] ?? [];
$totalPrice = 0;

include 'nitropack-config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - KickLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
    <style>
        .cart-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .cart-item img {
            width: 80px;
            height: auto;
            object-fit: cover;
        }
        .cart-summary-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }
        .remove-btn {
            cursor: pointer;
            color: red;
            font-size: 20px;
        }
        .promo-input {
            width: 100%;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">Your Cart</h2>

    <?php if (empty($cart)): ?>
        <div class="text-center">
            <h4>Your cart is empty.</h4>
            <a href="index.php" class="btn btn-outline-dark mt-3">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="row g-5">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <button class="btn btn-link text-danger mb-3 btn-clear-cart">Clear Cart</button>
                <?php foreach ($cart as $item): 
                    $total = $item['price'] * $item['quantity'];
                    $totalPrice += $total;
                ?>
                    <div class="cart-item row align-items-center">
                        <div class="col-md-2">
                            <a href="product.php?id=<?php echo $item['id']; ?>">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-fluid">
                            </a>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                            <small>Size: <?php echo $item['size']; ?></small>
                        </div>
                        <div class="col-md-2">
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" class="form-control update-quantity" data-id="<?php echo $item['id']; ?>">
                        </div>
                        <div class="col-md-2">
                            <span class="item-price" data-price="<?php echo $item['price']; ?>">
                                $<?php echo number_format($total, 2); ?>
                            </span>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="remove-btn" data-id="<?php echo $item['id']; ?>">&times;</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div class="col-lg-4">
                <div class="cart-summary-box">
                    <h5 class="mb-3">Order Summary</h5>
                    <div class="mb-3">
                        <label for="promoCode" class="form-label">Promo Code</label>
                        <input type="text" class="form-control promo-input" id="promoCode" placeholder="Enter your code">
                        <button class="btn btn-outline-dark w-100 mt-2">Apply</button>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($totalPrice, 2); ?></span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Shipping</span>
                        <span>$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span id="cartTotal">$<?php echo number_format($totalPrice, 2); ?></span>
                    </div>
                    <button class="btn btn-dark mt-3 w-100">Proceed to Checkout</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const cartContainer = document.querySelector(".cart-container");

    function updateTotal() {
        let total = 0;
        document.querySelectorAll(".cart-item").forEach(item => {
            const quantity = parseInt(item.querySelector(".update-quantity")?.value || 0);
            const price = parseFloat(item.querySelector(".item-price")?.dataset.price || 0);
            total += quantity * price;
        });
        document.querySelector("#cartTotal").textContent = "$" + total.toFixed(2);
    }

    document.querySelectorAll(".remove-btn").forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            fetch("remove_from_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + id,
            })
                .then(() => {
                    this.closest(".cart-item").remove();
                    updateTotal();
                });
        });
    });

    document.querySelectorAll(".update-quantity").forEach(input => {
        input.addEventListener("input", function () {
            const id = this.dataset.id;
            const quantity = this.value;
            const priceElement = this.closest(".cart-item").querySelector(".item-price");
            const itemTotal = quantity * parseFloat(priceElement.dataset.price);

            priceElement.textContent = "$" + itemTotal.toFixed(2);

            fetch("update_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}&quantity=${quantity}`,
            }).then(() => updateTotal());
        });
    });

    document.querySelector(".btn-clear-cart")?.addEventListener("click", function (e) {
        e.preventDefault();
        fetch("clear_cart.php", { method: "POST" })
            .then(() => {
                cartContainer.innerHTML = `
                    <div class="text-center mt-4">
                        <h4>Your cart is empty.</h4>
                        <a href="index.php" class="btn btn-outline-dark mt-3">Continue Shopping</a>
                    </div>`;
            });
    });
});
</script>


<?php include 'footer.php'; ?>
</body>
</html>
