<?php
require_once '../../../core/Database.php';
require_once '../../../models/OrderModel.php';
require_once '../../../models/TableModel.php';
require_once '../../../models/EmployeeModel.php';

$database = new Database();
$db = $database->getConnection();

$orderModel = new OrderModel($db);
$tableModel = new TableModel($db);
$employeeModel = new EmployeeModel($db);

$orderId = $_GET['id'] ?? null;
$orderData = null;
$message = '';

if ($orderId) {
    $orderData = $orderModel->getOrder($orderId);
    if (!$orderData) {
        $message = "Order not found.";
    }
} else {
    $message = "No Order ID provided.";
}

// Fetch all tables and employees
$tables = $tableModel->getAllTables();
$employees = $employeeModel->getAll();

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_order'])) {
    // Retrieve and process form data
    $tableId = $_POST['table_id'] ?? null;
    $employeeId = $_POST['employee_id'] ?? null;
    $dineIn = isset($_POST['dine_in']) ? 1 : 0;
    $completed = isset($_POST['completed']) ? 1 : 0;

    // Update the order
    $updateSuccess = $orderModel->updateOrder($orderId, $tableId, $employeeId, $dineIn, $completed);

    if ($updateSuccess) {
        $message = "Order updated successfully.";
        header('Location: ../../admin/view_edit_table.php?table=orders');
        exit;
    } else {
        $message = "Error updating order.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order</title>
</head>
<body>
    <h1>Edit Order</h1>
    <p><a href="../../admin/view_edit_table.php?table=orders">Back to View/Edit Orders</a></p>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($orderData): ?>
        <form action="orders_edit.php?id=<?= htmlspecialchars($orderId) ?>" method="post">
            <!-- Dropdown for Table Selection -->
            <select name="table_id" required>
                <?php foreach ($tables as $table): ?>
                    <option value="<?= htmlspecialchars($table['TableID']) ?>" <?= $table['TableID'] == $orderData['TableID'] ? 'selected' : '' ?>>
                        Table <?= htmlspecialchars($table['TableID']) ?> - Capacity: <?= htmlspecialchars($table['Capacity']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Dropdown for Employee Selection -->
            <select name="employee_id" required>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= htmlspecialchars($employee['EmployeeID']) ?>" <?= $employee['EmployeeID'] == $orderData['EmployeeID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($employee['FirstName'] . ' ' . $employee['LastName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Checkbox for Dine-In -->
            <div>
                <input type="checkbox" id="dine_in" name="dine_in" value="1" <?= $orderData['DineIn'] ? 'checked' : '' ?>>
                <label for="dine_in">Dine-In</label>
            </div>

            <button type="submit" name="update_order">Update Order</button>
        </form>
    <?php else: ?>
        <p>Order data not available.</p>
    <?php endif; ?>

</body>
</html>
