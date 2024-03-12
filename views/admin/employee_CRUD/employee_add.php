<?php
// add_employee.php
require_once '../../../core/Database.php';
require_once '../../../models/EmployeeModel.php';
require_once '../../../models/RoleModel.php';

$database = new Database();
$db = $database->getConnection();
$roleModel = new RoleModel($db);
$roles = $roleModel->getAllRoles(); // Fetch roles here

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employeeModel = new EmployeeModel($db);

    // Retrieve and process form data
    $firstName = ucwords(strtolower($_POST['first_name'] ?? ''));  // Enforce title case
    $lastName = ucwords(strtolower($_POST['last_name'] ?? ''));   // Enforce title case
    $roleId = $_POST['role_id'] ?? null;
    $wage = $_POST['wage'] ?? null;
    
    // Handling file upload
    if (isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../../../assets/images/employees/";
        $filename = basename($_FILES["employee_image"]["name"]);
        $targetFilePath = $targetDir . $filename;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

       
        $check = getimagesize($_FILES["employee_image"]["tmp_name"]);
        if($check !== false) {
            // Attempt to move the uploaded file to your target directory
            if (move_uploaded_file($_FILES["employee_image"]["tmp_name"], $targetFilePath)) {
                // File upload success
                $imageUrl = 'assets/images/employees/' . $filename;
            } else {
                // File upload error
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $message = "File is not an image.";
        }
    }

    // Add the employee
    if ($employeeModel->addEmployee($firstName, $lastName, $roleId, $wage, $imageUrl ?? null)) {
        $message = "Employee added successfully.";
    } else {
        $message = "Error adding employee.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Add Employee</h1>

        <!-- Back to Dashboard Button -->
        <div class="text-center mb-3">
            <a href="../../admin/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <!-- Message Display -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Add Employee Form -->
        <form action="employee_add.php" method="post" enctype="multipart/form-data" class="border p-4 rounded">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" id="first_name" placeholder="First Name" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Role:</label>
                <select name="role_id" id="role_id" required class="form-select">
                    <option value="">Select a Role</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= htmlspecialchars($role['RoleID']) ?>"><?= htmlspecialchars($role['RoleName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="wage" class="form-label">Wage:</label>
                <input type="number" step="0.01" name="wage" id="wage" placeholder="Wage" required class="form-control">
            </div>
            
            <div class="mb-3">
                <label for="employee_image" class="form-label">Employee Image:</label>
                <input type="file" id="employee_image" name="employee_image" accept="image/*" class="form-control" onchange="previewImage(event)">
                <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
            </div>

            <button type="submit" class="btn btn-primary">Add Employee</button>
        </form>
    </div>
    
     <!-- JavaScript for Image Preview -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
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
