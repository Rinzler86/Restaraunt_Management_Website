<?php
require_once '../../../core/Database.php';
require_once '../../../models/EmployeeModel.php';
require_once '../../../models/RoleModel.php';

$database = new Database();
$db = $database->getConnection();
$employeeModel = new EmployeeModel($db);
$roleModel = new RoleModel($db);

$employeeId = $_GET['id'] ?? null;
$employeeData = null;
$message = '';

if ($employeeId) {
    $employeeData = $employeeModel->getEmployee($employeeId);
    if (!$employeeData) {
        $message = "Employee not found.";
    }
} else {
    $message = "No Employee ID provided.";
}

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_employee'])) {
    // Retrieve form data and apply title case
    $employeeId = $_POST['employee_id'] ?? null;
    $firstName = ucwords(strtolower($_POST['first_name'] ?? ''));
    $lastName = ucwords(strtolower($_POST['last_name'] ?? ''));
    $roleId = $_POST['role_id'] ?? null;
    $wage = $_POST['wage'] ?? null;

    // Handle image upload
    $imageUrl = null;
    if (isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../../../assets/images/employees/"; 
        $filename = basename($_FILES["employee_image"]["name"]);
        $targetFilePath = $targetDir . $filename;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Validate the file (check image type, size, etc.)
        $check = getimagesize($_FILES["employee_image"]["tmp_name"]);
        if($check !== false) {
            // Move the file to the target directory
            if (move_uploaded_file($_FILES["employee_image"]["tmp_name"], $targetFilePath)) {
                $imageUrl = 'assets/images/employees/' . $filename;
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $message = "File is not an image.";
        }
    }

    // Update the employee, including the new image URL if uploaded
    $updateSuccess = $employeeModel->updateEmployee($employeeId, $firstName, $lastName, $roleId, $wage, $imageUrl);

    if ($updateSuccess) {
        // Redirect or display success message
        header('Location: ../../admin/view_edit_table.php?table=employees'); // Redirect after successful update
        exit;
    } else {
        $message = "Error updating employee.";
    }
}

// Handle form submission for deleting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_employee'])) {
    if ($employeeModel->deleteEmployee($employeeId)) {
        header('Location: ../../admin/dashboard.php'); // Redirect after delete
        exit;
    } else {
        $message = "Error deleting employee.";
    }
}

$roles = $roleModel->getAllRoles(); // Fetch all roles for the dropdown

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../../../assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Employee</h1>

        <!-- Back to View/Edit Employees Button -->
        <div class="text-center mb-3">
            <a href="../../admin/view_edit_table.php?table=employees" class="btn btn-secondary">Back to View/Edit Employees</a>
        </div>

        <!-- Message Display -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($employeeData): ?>
            <!-- Employee Card -->
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <?php
                            // Construct the image path
                            $defaultImagePath = 'assets/images/deluxepizzaandpasta2.jpg';
                            $employeeImagePath = $employeeData['ImageUrl'] ? "../../../" . htmlspecialchars($employeeData['ImageUrl']) : $defaultImagePath;
                        ?>
                        <img src="<?= $employeeImagePath ?>" class="card-img employee-image" alt="Employee Image">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($employeeData['FirstName']) . ' ' . htmlspecialchars($employeeData['LastName']) ?></h5>
                            
                            <p class="card-text">Wage: $<?= htmlspecialchars($employeeData['Wage']) ?></p>

                            <!-- Edit Employee Form -->
                            <form action="employee_edit.php?id=<?= htmlspecialchars($employeeId) ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employeeId) ?>">
                                
                                <div class="form-group">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($employeeData['FirstName']) ?>" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($employeeData['LastName']) ?>" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="role_id">Role:</label>
                                    <select name="role_id" id="role_id" required class="form-control">
                                        <option value="">Select a Role</option>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= htmlspecialchars($role['RoleID']) ?>" <?= $role['RoleID'] == $employeeData['RoleID'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($role['RoleName']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="wage">Wage:</label>
                                    <input type="number" step="0.01" id="wage" name="wage" value="<?= htmlspecialchars($employeeData['Wage']) ?>" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="employee_image">Employee Image:</label>
                                    <input type="file" id="employee_image" name="employee_image" accept="image/*" class="form-control-file">
                                </div>
                                <button type="submit" name="update_employee" class="btn btn-primary mt-2">Update Employee</button>
                            </form>
                            
                            <!-- Delete Button -->
                            <form action="employee_edit.php?id=<?= htmlspecialchars($employeeId) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this employee?');" class="mt-2">
                                <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employeeId) ?>">
                                <button type="submit" name="delete_employee" class="btn btn-danger">Delete Employee</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>Employee data not available.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
