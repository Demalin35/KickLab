<?php
session_start();
$cartItems = $_SESSION["cart"] ?? [];
$cartCount = count($cartItems);
$isLoggedIn = isset($_SESSION['user']);
$user = $_SESSION['user'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4">
    <!-- LOGO -->
    <a class="navbar-brand text-success fw-bold" href="index.php">
        <img src="assets/logo.png" alt="Logo" style="width: 100px; height: auto;">
    </a>

    <!-- MOBILE ICONS & TOGGLER -->
    <div class="d-flex align-items-center gap-3 d-lg-none ms-auto">
        <a href="wishlist.php"><i class="bi bi-heart fs-4"></i></a>
        <a href="account.php"><i class="bi bi-person fs-4"></i></a>
        <a href="cart.php" class="position-relative">
            <i class="bi bi-cart fs-4"></i>
            <?php if ($cartCount > 0): ?>
                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                    <?php echo $cartCount; ?>
                </span>
            <?php endif; ?>
        </a>

        <!-- Hamburger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>


    <!-- NAV LINKS -->
    <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link"
                    href="https://dodgerblue-loris-317943.hostingersite.com/newcollection.php">New collection</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="sale.php">Sale</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Brands</a></li>
            <li class="nav-item"><a class="nav-link" href="women.php">Women</a></li>
            <li class="nav-item"><a class="nav-link" href="men.php">Men</a></li>
        </ul>

        <!-- SEARCH -->
        <form class="d-flex me-3" action="search.php" method="GET">
            <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

        <!-- DESKTOP ICONS + Welcome -->
        <ul class="navbar-nav d-none d-lg-flex align-items-center">

            <li class="nav-item me-3">
                <a class="nav-link" href="wishlist.php"><i class="bi bi-heart"></i> Wishlist</a>
            </li>

            <?php if ($isLoggedIn): ?>
                <li class="nav-item me-3 position-relative account-container d-none d-lg-block">
                    <a class="nav-link icon-link" href="#"><i class="bi bi-person"></i> Account</a>
                    <div class="account-popup shadow-lg bg-white rounded position-absolute">
                        <div class="account-popup-content text-center p-3">
                            <i class="bi bi-person-circle fs-1 text-success mb-2"></i>
                            <h6 class="fw-bold mb-2">Hello, <?= htmlspecialchars($user['first_name']) ?>!</h6>
                            <a href="profile.php" class="btn btn-outline-secondary w-100 mb-2">My Profile</a>
                            <a href="orders.php" class="btn btn-outline-secondary w-100 mb-2">My Orders</a>
                            <a href="track_order.php" class="btn btn-outline-secondary w-100 mb-2">Track Order</a>
                            <a href="logout.php" class="btn btn-outline-danger w-100">Logout</a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>


    </div>
    </div>
    </li>



    <!-- Account Block -->
    <?php if (!$isLoggedIn): ?>
        <li class="nav-item me-3 position-relative account-container d-none d-lg-block">
            <a class="nav-link icon-link" href="#"><i class="bi bi-person"></i> Account</a>
            <div class="account-popup shadow-lg bg-white rounded position-absolute">
                <div class="account-popup-content text-center p-3">
                    <h6 class="fw-bold mb-2">Welcome to the store!</h6>
                    <a href="login.php" class="btn btn-success btn-login mb-2">Login</a>
                    <a href="register.php" class="btn btn-outline-success btn-create">Create Account</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Cart block -->
    <li class="nav-item me-3 position-relative cart-container d-none d-lg-block">
        <a class="nav-link icon-link" href="cart.php" id="cartIcon">
            <i class="bi bi-cart"></i>
            <span class="d-none d-lg-inline">Cart (<span id="cartCount"><?php echo $cartCount; ?></span>)</span>
        </a>

        <div class="cart-popup shadow-lg bg-white rounded position-absolute">
            <div class="cart-popup-content p-3">
                <h6 class="fw-bold mb-3">Your Cart</h6>

                <?php if (empty($cartItems)): ?>
                    <p class="text-muted">Your cart is empty.</p>
                <?php else: ?>
                    <ul class="list-group cart-item-list mb-3">
                        <?php foreach ($cartItems as $index => $item): ?>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <img src="<?php echo $item['image']; ?>" alt="thumb" width="45" height="45"
                                    class="rounded me-2">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold"><?php echo $item["name"]; ?></div>
                                    <small class="text-muted">Size: <?php echo $item["size"]; ?></small>
                                </div>
                                <div class="text-end">
                                    <div>$<?php echo number_format($item["price"], 2); ?></div>
                                    <form action="remove_from_cart.php" method="POST" class="d-inline">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="cart.php" class="btn btn-dark w-100">Go to Cart</a>
                <?php endif; ?>
            </div>
        </div>
    </li>
</nav>



<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">

<style>
    .navbar-icons {
        display: flex;
        align-items: center;
        gap: 24px;
        font-family: 'Segoe UI', sans-serif;
        font-size: 15px;
    }

    .navbar-icons a {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #36344D;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .navbar-icons a:hover {
        color: #2f7c3c;
    }

    .navbar-icons a i {
        font-size: 20px;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .navbar-icons a:hover i {
        transform: scale(1.2);
        color: #2f7c3c;
    }

    .navbar-icons .cart-count {
        font-size: 13px;
        color: #888;
        margin-left: 4px;
    }


    .navbar-icons .cart-count {
        font-size: 13px;
        color: #888;
        margin-left: 4px;
    }

    .navbar-nav .nav-link i {
        margin-right: 6px;
        font-size: 18px;
        vertical-align: middle;
    }

    .account-popup,
    .cart-popup {
        display: none;
        width: 320px;
        top: 110%;
        right: 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .cart-container:hover .cart-popup,
    .account-container:hover .account-popup {
        display: block;
    }

    .cart-item-list img {
        object-fit: cover;
    }

    .account-popup .btn-login,
    .cart-popup .btn-login {
        background-color: #28a745;
        color: #fff;
    }

    .account-popup .btn-create,
    .cart-popup .btn-create {
        border: 1px solid #28a745;
        color: #28a745;
    }

    .account-popup .btn-create:hover,
    .cart-popup .btn-create:hover {
        background-color: #28a745;
        color: #fff;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const accountContainer = document.querySelector('.account-container');
        const cartContainer = document.querySelector('.cart-container');
        const cartPopup = document.querySelector('.cart-popup');
        const accountPopup = document.querySelector('.account-popup');

        accountContainer.addEventListener('mouseenter', function () {
            accountPopup.style.display = 'block';
        });
        accountContainer.addEventListener('mouseleave', function () {
            accountPopup.style.display = 'none';
        });

        cartContainer.addEventListener('mouseenter', function () {
            cartPopup.style.display = 'block';
        });
        cartContainer.addEventListener('mouseleave', function () {
            cartPopup.style.display = 'none';
        });
    });
</script>