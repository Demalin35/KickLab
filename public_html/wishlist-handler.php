<?php
session_start();

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $productId = $_POST['product_id'];

    if ($action === 'add') {
        if (!in_array($productId, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $productId;
            echo json_encode(['status' => 'success', 'message' => 'Product added to wishlist']);
        } else {
            echo json_encode(['status' => 'exists', 'message' => 'Product already in wishlist']);
        }
    } elseif ($action === 'remove') {
        if (($key = array_search($productId, $_SESSION['wishlist'])) !== false) {
            unset($_SESSION['wishlist'][$key]);
            echo json_encode(['status' => 'success', 'message' => 'Product removed from wishlist']);
        } else {
            echo json_encode(['status' => 'not_found', 'message' => 'Product not found in wishlist']);
        }
    }
}

?>
<?php
session_start();

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $productId = $_POST['product_id'];

    if ($action === 'add') {
        if (!in_array($productId, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $productId;
            echo json_encode(['status' => 'success', 'message' => 'Product added to wishlist']);
        } else {
            echo json_encode(['status' => 'exists', 'message' => 'Product already in wishlist']);
        }
    } elseif ($action === 'remove') {
        if (($key = array_search($productId, $_SESSION['wishlist'])) !== false) {
            unset($_SESSION['wishlist'][$key]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
            echo json_encode(['status' => 'success', 'message' => 'Product removed from wishlist']);
        } else {
            echo json_encode(['status' => 'not_found', 'message' => 'Product not found in wishlist']);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action === 'count') {
        echo count($_SESSION['wishlist']);
        exit;
    }

    if ($action === 'get') {
        echo json_encode($_SESSION['wishlist']);
        exit;
    }
}
?>