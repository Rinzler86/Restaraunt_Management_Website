<?php
session_start();// Initialize cart if not already set

require_once '../../core/Database.php';
require_once '../../models/FoodModel.php';

$database = new Database();
$db = $database->getConnection();
$foodModel = new FoodModel($db);
$dishes = $foodModel->getAllFoods();

$toppings = []; // Initialize an empty array for toppings
// SQL query to fetch toppings
$sql = "SELECT ToppingID, Name, Price FROM toppings";
$stmt = $db->query($sql);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $toppings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    
    <meta charset="UTF-8">
    <title>Menu</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    
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
    <h1>Our Menu</h1>
    <div class="row">
            <?php foreach ($dishes as $dish): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="img-wrapper">
                            <img src="../../<?= htmlspecialchars($dish['ImageUrl']) ?>" class="card-img-top" alt="<?= htmlspecialchars($dish['Name']) ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($dish['Name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($dish['Description']) ?></p>
                            <p class="card-text">Price: $<?= htmlspecialchars($dish['Price']) ?></p>
                            <!-- Customize Button -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customizeModal<?= $dish['FoodID'] ?>">
                                Customize
                            </button>
                            
                            <!-- Customize Modal -->
                            <div class="modal fade" id="customizeModal<?= $dish['FoodID'] ?>" tabindex="-1" aria-labelledby="customizeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="customizeModalLabel">Customize <?= htmlspecialchars($dish['Name']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Toppings Checkboxes -->
                                            <?php foreach ($toppings as $topping): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input topping-checkbox" type="checkbox" value="<?= htmlspecialchars($topping['Price']) ?>" id="topping<?= $topping['ToppingID'] . $dish['FoodID'] ?>" data-food-id="<?= $dish['FoodID'] ?>">
                                                    <label class="form-check-label" for="topping<?= $topping['ToppingID'] . $dish['FoodID'] ?>">
                                                        <?= htmlspecialchars($topping['Name']) ?> (+$<?= htmlspecialchars($topping['Price']) ?>)
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                            <!-- Running Total -->
                                            <p>Total: $<span id="totalPrice<?= $dish['FoodID'] ?>"><?= htmlspecialchars($dish['Price']) ?></span></p>
                                        </div>
                                        <button type="button" class="btn btn-primary add-to-cart-btn" data-food-id="<?= $dish['FoodID'] ?>" data-bs-dismiss="modal">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
   
    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start">
        <div class="text-center p-3">
            &copy; <span id="year"></span> Morristown Pasta & Pizza
        </div>
    </footer>
    
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    
    <!-- Dynamic Year Script -->
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
    
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.topping-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    let foodId = this.dataset.foodId;
                    let totalPriceElement = document.getElementById('totalPrice' + foodId);
                    let currentPrice = parseFloat(totalPriceElement.textContent);
                    let toppingPrice = parseFloat(this.value);
                    if (this.checked) {
                        currentPrice += toppingPrice;
                    } else {
                        currentPrice -= toppingPrice;
                    }
                    totalPriceElement.textContent = currentPrice.toFixed(2);
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                let foodId = this.dataset.foodId;
                let toppings = [];
                document.querySelectorAll('#customizeModal' + foodId + ' .topping-checkbox:checked').forEach(checkbox => {
                    toppings.push(checkbox.id.replace('topping', ''));
                });

                // Debugging log
                console.log("Food ID:", foodId);
                console.log("Toppings:", toppings);
                console.log("Quantity: 1");

                    let requestData = {
                        action: 'add',
                        foodId: foodId,
                        toppings: toppings,
                        quantity: 1 // Assuming a quantity of 1 for simplicity
                    };

                    console.log("Sending Request:", requestData);

                    fetch('../cart/cart-operation.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(requestData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Response:", data);
                        if(data.status === 'success') {
                            window.location.reload(); // Reload the page to update the cart
                        } else {
                            alert("Error adding item to cart: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("An error occurred while adding the item to the cart.");
                    });
                });
            });
        });

    </script>

    
</body>
</html>


