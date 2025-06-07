<?php
session_start();


// Sample product data
$product = [
    "id" => 1,
    "name" => "Adidas Terrex Anylander",
    "brand" => "Adidas",
    "price" => 135.00,
    "colors" => ["Black", "Olive"],
    "sizes" => ["39 1/3", "40", "40 2/3", "41 1/3", "42", "42 2/3", "43 1/3", "44", "44 2/3", "45 1/3", "46", "46 2/3", "47 1/3", "48"],
    "imagePaths" => ["assets/terrex1.webp", "assets/terrex2.webp", "assets/terrex3.jpg", "assets/terrex4.jpg"],
    "description" => "High performance outdoor shoes with EVA and Traxion technology."
];

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedSize = $_POST["size"] ?? null;
    $quantity = isset($_POST["quantity"]) ? (int) $_POST["quantity"] : 1;

    if ($selectedSize) {
        $cartItem = [
            "id" => $product["id"],
            "name" => $product["name"],
            "price" => $product["price"],
            "size" => $selectedSize,
            "quantity" => $quantity,
            "image" => $product["imagePaths"][0]
        ];

        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        $_SESSION["cart"][] = $cartItem;

        header("Location: cart.php");
        exit;
    } else {
        $error = "Please select a size before adding to the cart.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $product["name"]; ?> | Obuvki</title>
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

        .product-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container product-container">
        <div class="row g-4">
            <!-- Thumbnail Slider -->
            <div class="col-md-1 thumbnail-slider">
                <?php foreach ($product["imagePaths"] as $index => $image): ?>
                    <img src="<?php echo $image; ?>" class="thumbnail" data-image="<?php echo $image; ?>"
                        alt="Product Thumbnail">
                <?php endforeach; ?>
            </div>

            <!-- Main Image Display -->
            <div class="col-md-6 main-image-container">
                <img src="<?php echo $product["imagePaths"][0]; ?>" id="mainImage" class="main-image"
                    data-bs-toggle="modal" data-bs-target="#imageModal" alt="Main Product Image">
            </div>

            <!-- Product Details -->
            <div class="col-md-5">
                <div class="product-details">
                    <h1><?php echo $product["brand"]; ?> - <?php echo $product["name"]; ?></h1>
                    <p class="text-muted">Color: Black / Olive</p>
                    <p class="fw-bold text-danger"><?php echo number_format($product["price"], 2); ?> USD</p>

                    <form id="addToCartForm">
                        <input type="hidden" name="quantity" value="1">

                        <div class="size-selection mt-3">
                            <?php foreach ($product["sizes"] as $size): ?>
                                <label class="size-box">
                                    <input type="radio" name="size" value="<?php echo $size; ?>" class="d-none">
                                    <?php echo $size; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div id="cartMessage" class="text-danger mt-2" style="display:none;"></div>

                        <button type="submit" class="add-to-cart-btn mt-4">Add to Cart</button>
                    </form>


                    <!-- Product Info -->
                    <div class="product-info">
                        <p><?php echo $product["description"]; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
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
                <p><strong>Brand:</strong> Adidas</p>
                <p><strong>Color:</strong> Black, Olive</p>
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
            document.querySelectorAll('.size-box').forEach(box => {
                box.addEventListener('click', function () {
                    document.querySelectorAll('.size-box').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>


    <script>
        const cart = [];

        document.getElementById("addToCartBtn").addEventListener("click", function () {
            const sizeSelect = document.getElementById("sizeSelect");
            const selectedSize = sizeSelect.value;
            const cartMessage = document.getElementById("cartMessage");

            if (selectedSize === "") {
                cartMessage.style.display = "block";
                cartMessage.textContent = "Please select a size before adding to the cart.";
            } else {
                cartMessage.style.display = "none";

                const product = {
                    id: 1,
                    name: "Adidas Terrex",
                    price: 135.00,
                    size: selectedSize
                };

                cart.push(product);
                updateCart();
            }
        });

        function updateCart() {
            const cartItems = document.getElementById("cartItems");
            cartItems.innerHTML = "";

            if (cart.length === 0) {
                cartItems.innerHTML = `<li class="list-group-item">Your cart is empty.</li>`;
            } else {
                cart.forEach((item, index) => {
                    cartItems.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${item.name} - Size: ${item.size} - $${item.price.toFixed(2)}
                        <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">Remove</button>
                    </li>
                `;
                });
            }
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        updateCart(); // Initialize cart display
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("addToCartForm");
            const message = document.getElementById("cartMessage");
            const sizeBoxes = document.querySelectorAll(".size-box");

            sizeBoxes.forEach(label => {
                label.addEventListener("click", () => {
                    sizeBoxes.forEach(lb => lb.classList.remove("active"));
                    label.classList.add("active");
                    label.querySelector("input").checked = true;
                });
            });

            form.addEventListener("submit", function (e) {
                e.preventDefault();

                const formData = new FormData(form);

                if (!formData.get("size")) {
                    message.style.display = "block";
                    message.textContent = "Please select a size before adding to the cart.";
                    return;
                }

                fetch("add_to_cart.php", {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            message.style.display = "none";
                            document.getElementById("cartCount").textContent = data.count;
                        } else {
                            message.style.display = "block";
                            message.textContent = data.error || "Failed to add to cart.";
                        }
                    })
                    .catch(err => {
                        message.style.display = "block";
                        message.textContent = "Error adding to cart.";
                    });
            });
        });
    </script>


</body>

</html>