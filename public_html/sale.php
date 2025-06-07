<?php
session_start();
?>

<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Special Offer - KickLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
    <style>
        .sale-banner {
            background-color: #000;
            color: #fff;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .sale-banner h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .filters-container {
            display: flex;
            gap: 15px;
            padding: 1rem 0;
            overflow-x: auto;
        }

        .filter-item {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
        }

        .filter-item.active {
            background-color: #4CAF50;
            color: #fff;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: auto;
            margin-bottom: 1rem;
        }

        .brand-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 2rem;
        }

        .brand-item {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #ddd;
        }

        @media (max-width: 576px) {
            .filters-container {
                flex-wrap: nowrap;
                overflow-x: scroll;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Sale Banner -->
    <div class="sale-banner">
        <h1>SPECIAL OFFER - Selected Products at Best Prices!</h1>
    </div>

    <!-- Filters Section -->
    <div class="container">
        <div class="filters-container">
            <div class="filter-item active" data-category="all">All</div>
            <div class="filter-item" data-category="unisex">Unisex</div>
            <div class="filter-item" data-category="accessories">Accessories</div>
            <div class="filter-item" data-category="women">Women</div>
            <div class="filter-item" data-category="men">Men</div>
            <div class="filter-item" data-category="kids">Kids</div>
            <div class="filter-item" data-category="sports">Sports</div>
            <div class="filter-item" data-category="bags">Bags</div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="container my-4">
        <div class="products-grid" id="productsGrid">
            <!-- Products will be dynamically loaded here -->
        </div>
    </div>

    <!-- Brand Section -->
    <div class="container">
        <h5>Popular Brands in this Category</h5>
        <div class="brand-list">
            <span class="brand-item">Nike</span>
            <span class="brand-item">Adidas</span>
            <span class="brand-item">Reebok</span>
            <span class="brand-item">Guess</span>
            <span class="brand-item">Tommy Hilfiger</span>
            <span class="brand-item">New Balance</span>
            <span class="brand-item">Puma</span>
            <span class="brand-item">Under Armour</span>
            <span class="brand-item">Calvin Klein</span>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
        const products = [
            { id: 1, name: "Nike Air Force 1", price: 99.99, category: "unisex", link: "product.php?id=1", image: "assets/nikeair.webp" },
            { id: 2, name: "Adidas Ultraboost", price: 120.00, category: "unisex", link: "product.php?id=2", image: "assets/adidasultraboost.webp" },
            { id: 3, name: "Reebok Club C", price: 89.50, category: "women", link: "product.php?id=3", image: "assets/reebokclubc.webp" },
            { id: 4, name: "Tommy Hilfiger Sandals", price: 75.00, category: "women", link: "product.php?id=4", image: "assets/tommy_hilfiger_sandals.webp" },
            { id: 5, name: "Calvin Klein Sneakers", price: 110.00, category: "men", link: "product.php?id=5", image: "assets/calvin_klein_sneakers.webp" },
            { id: 6, name: "Puma RS-X", price: 130.00, category: "men", link: "product.php?id=6", image: "assets/puma_rs_x.webp" }
        ];

        function renderProducts(category) {
            const grid = document.getElementById("productsGrid");
            grid.innerHTML = "";
            const filteredProducts = category === "all" ? products : products.filter(p => p.category === category);

            filteredProducts.forEach(product => {
                grid.innerHTML += `
            <div class="product-card">
                <a href="${product.link}" class="text-decoration-none">
                    <img src="${product.image}" alt="${product.name}" class="img-fluid mb-2">
                    <h6>${product.name}</h6>
                    <p>$${product.price.toFixed(2)}</p>
                    <a href="${product.link}" class="btn btn-success">View Product</a>
                </a>
            </div>
        `;
            });
        }


        document.querySelectorAll(".filter-item").forEach(item => {
            item.addEventListener("click", function () {
                document.querySelectorAll(".filter-item").forEach(el => el.classList.remove("active"));
                this.classList.add("active");
                const category = this.getAttribute("data-category");
                renderProducts(category);
            });
        });

        renderProducts("all");
    </script>

</body>

</html>