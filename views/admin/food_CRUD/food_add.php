<?php
require_once '../../../core/Database.php';
require_once '../../../models/FoodModel.php';

$database = new Database();
$db = $database->getConnection();
$foodModel = new FoodModel($db);

$message = ''; // Initialize $message to an empty string

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0.00;
    $inStock = $_POST['in_stock'] ?? 0;

    $imageUrl = null;
    if (isset($_FILES['food_image']) && $_FILES['food_image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../../../assets/images/foods/";
        $filename = basename($_FILES["food_image"]["name"]);
        $targetFilePath = $targetDir . $filename;

        if (move_uploaded_file($_FILES["food_image"]["tmp_name"], $targetFilePath)) {
            $imageUrl = 'assets/images/foods/' . $filename;
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }

    // Add the food item
    if ($foodModel->addFood($name, $description, $price, $inStock, $imageUrl)) {
        $message = "Food item added successfully.";
    } else {
        $message = "Error adding food item.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Food Item</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../../../assets/css/styles.css">
    
    <style>
        .image-preview {
            width: 150px;
            height: 150px;
            border: 2px solid #dddddd;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #cccccc;
        }
        .image-preview img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Add Food Item</h1>

        <div class="text-center mb-3">
            <a href="../../admin/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="food_add.php" method="post" enctype="multipart/form-data" class="border p-4 rounded">
            <div class="mb-3">
                <label for="name" class="form-label">Food Name:</label>
                <input type="text" name="name" id="name" placeholder="Food Name" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea name="description" id="description" placeholder="Description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" step="0.01" name="price" id="price" placeholder="Price" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="in_stock" class="form-label">In Stock:</label>
                <input type="number" name="in_stock" id="in_stock" placeholder="In Stock" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="food_image" class="form-label">Food Image:</label>
                <input type="file" id="food_image" name="food_image" accept="image/*" class="form-control" onchange="previewImage(event)">
                <div class="image-preview" id="imagePreview">
                    <span>Image Preview</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Food Item</button>
        </form>
    </div>

    <!-- JavaScript for Image Preview -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.innerHTML = '<img src="' + reader.result + '">';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
