<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // Redirect or show an error if not a POST request
    header("Location: some_error_page.php"); // Redirect to a designated error page or home page
    exit;
}

session_start();

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($foodId, $toppings, $quantity) {
    $cartItem = [
        'foodId' => $foodId,
        'toppings' => $toppings,
        'quantity' => $quantity
    ];

    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['foodId'] == $foodId) {
            // Update item if it exists
            $_SESSION['cart'][$key] = $cartItem;
            return ['status' => 'success', 'message' => 'Item updated in cart'];
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = $cartItem;
     error_log("Current cart contents: " . print_r($_SESSION['cart'], true));
     return ['status' => 'success', 'message' => 'Item added to cart'];
    
    
}
error_log("Final cart contents: " . print_r($_SESSION['cart'], true));

// Function to remove an item from the cart
function removeFromCart($foodId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['foodId'] == $foodId) {
            unset($_SESSION['cart'][$key]);
            return ['status' => 'success', 'message' => 'Item removed from cart'];
        }
    }
    return ['status' => 'error', 'message' => 'Item not found in cart'];
}

// Handling POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Log incoming request data for debugging
    error_log("Incoming request: " . print_r($data, true));

    if (isset($data['action'])) {
        switch ($data['action']) {
            case 'add':
                if (isset($data['foodId'], $data['toppings'], $data['quantity'])) {
                    $result = addToCart($data['foodId'], $data['toppings'], $data['quantity']);
                    echo json_encode($result);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Missing parameters for add action']);
                }
                break;
            case 'remove':
                if (isset($data['foodId'])) {
                    $result = removeFromCart($data['foodId']);
                    echo json_encode($result);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Missing food ID for remove action']);
                }
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid action specified']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No action specified in request']);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed, only POST is accepted']);
}
?>



