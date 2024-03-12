<?php
session_start();

require_once '../../core/Database.php';
require_once '../../models/FoodModel.php';

$database = new Database();
$db = $database->getConnection();
$foodModel = new FoodModel($db);

$cartItems = $_SESSION['cart'] ?? [];

function getFoodItemDetails($db, $foodId) {
    $stmt = $db->prepare("SELECT Name, Price FROM food WHERE FoodID = ?");
    if ($stmt->execute([$foodId])) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return ['Name' => 'Unknown', 'Price' => 0]; // Default values in case of error
    }
}

function getToppingDetails($db, $foodId) {
    $toppings = [];
    
    $stmt = $db->prepare("
        SELECT t.Name, t.Price
        FROM toppings t
        INNER JOIN food_toppings ft ON t.ToppingID = ft.ToppingID
        WHERE ft.FoodID = ?
    ");

    if ($stmt->execute([$foodId])) {
        $toppings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        error_log("Failed to execute query for FoodID: " . $foodId);
        error_log("PDOStatement::errorInfo(): " . print_r($stmt->errorInfo(), true));
    }

    return $toppings;
}


$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">
                <img src="../../assets/images/deluxepizzaandpasta2.png" alt="Deluxe Pasta and Pizza Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <a href="../cart/cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                        <?php if (count($_SESSION['cart']) > 0): ?>
                            <span class="cart-badge"><?= count($_SESSION['cart']) ?></span>
                        <?php endif; ?>
                    </a>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Your Cart</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Subtotal</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <?php
                            $foodDetails = getFoodItemDetails($db, $item['foodId']);
                            $toppingsDetails = getToppingDetails($db, $item['foodId']);
                            $itemSubtotal = $foodDetails['Price'];
                            $itemTotal = $itemSubtotal * $item['quantity'];
                            $totalPrice += $itemTotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($foodDetails['Name']) ?></td>
                            <td>$<?= htmlspecialchars(number_format($itemSubtotal, 2)) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= htmlspecialchars(number_format($itemTotal, 2)) ?></td>
                            <td>
                                <!-- Remove Button -->
                                <button class="btn btn-danger remove-item-btn" data-food-id="<?= $item['foodId'] ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                        <td>$<?= htmlspecialchars(number_format($totalPrice, 2)) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start">
        <div class="text-center p-3">
            &copy; <span id="year"></span> Morristown Pasta & Pizza
        </div>
    </footer>
    
    <!-- Dynamic Year Script -->
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
    

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- JavaScript for Remove Button -->
    <script>
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                let foodId = this.dataset.foodId;

                fetch('../cart/cart-operation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'remove', foodId: foodId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.reload(); // Reload the page to update the cart
                    } else {
                        alert("Error removing item from cart: " + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>


