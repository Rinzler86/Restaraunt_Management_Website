<?php
require_once '../../../core/Database.php';
require_once '../../../models/FoodModel.php';

$database = new Database();
$db = $database->getConnection();
$foodModel = new FoodModel($db);

$foodId = $_GET['id'] ?? null;
$foodData = null;
$message = ''; // Initialize $message to an empty string

// Fetch food data if $foodId is present
if ($foodId) {
    $foodData = $foodModel->getFood($foodId);
    if (!$foodData) {
        $message = "Food item not found.";
    }
} else {
    $message = "No Food ID provided.";
}

// Handle form submission for deleting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_food'])) {
    $foodId = $_POST['food_id'] ?? null;
    if ($foodId && $foodModel->deleteFood($foodId)) {
        header('Location: ../../admin/view_edit_table.php?table=food');
        exit;
    } else {
        $message = "Error deleting food item.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Food Item</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Food Item</h1>

        <div class="text-center mb-3">
            <a href="../../admin/view_edit_table.php?table=food" class="btn btn-secondary">Back to View/Edit Food Items</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($foodData): ?>
            <!-- Update Food Item Form -->
            <form action="food_edit.php?id=<?= htmlspecialchars($foodId) ?>" method="post" enctype="multipart/form-data" class="border p-4 rounded">
                <input type="hidden" name="food_id" value="<?= htmlspecialchars($foodId) ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($foodData['Name']) ?>" required class="form-control">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea id="description" name="description" required class="form-control"><?= htmlspecialchars($foodData['Description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price:</label>
                    <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($foodData['Price']) ?>" required class="form-control">
                </div>

                <div class="mb-3">
                    <label for="in_stock" class="form-label">In Stock:</label>
                    <input type="number" id="in_stock" name="in_stock" value="<?= htmlspecialchars($foodData['InStock']) ?>" required class="form-control">
                </div>

                <!-- Image Upload Field -->
                <div class="mb-3">
                    <label for="food_image" class="form-label">Food Image:</label>
                    <input type="file" id="food_image" name="food_image" accept="image/*" class="form-control">
                </div>

                <button type="submit" name="update_food" class="btn btn-primary">Update Food Item</button>
            </form>

            <!-- Delete Food Item Form -->
            <form action="food_edit.php?id=<?= htmlspecialchars($foodId) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this food item?');" class="mt-3">
                <input type="hidden" name="food_id" value="<?= htmlspecialchars($foodId) ?>">
                <button type="submit" name="delete_food" class="btn btn-danger">Delete Food Item</button>
            </form>
        <?php else: ?>
            <p class="text-center">Food item data not available.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
