<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../core/Database.php';
require_once '../../models/EmployeeModel.php';
require_once '../../models/FoodModel.php';
require_once '../../models/OrderModel.php';

$validTables = ['employees', 'food', 'orders'];

$table = $_GET['table'] ?? '';
$data = [];
$columns = [];

if (!in_array($table, $validTables)) {
    die("Invalid table specified.");
}

$database = new Database();
$db = $database->getConnection();

switch ($table) {
    case 'employees':
        $model = new EmployeeModel($db);
        $columns = ['EmployeeID', 'FirstName', 'LastName', 'RoleID', 'Wage', 'ImageUrl']; 
        break;
    case 'food':
        $model = new FoodModel($db);
        $columns = ['FoodID', 'Name', 'Description', 'Price', 'InStock'];
        break;
    case 'orders':
        $model = new OrderModel($db);
        $columns = ['OrderID', 'TableID', 'EmployeeID', 'OrderTime', 'DineIn', 'Completed'];
        break;
}

if (isset($model) && method_exists($model, 'getAll')) {
    $data = $model->getAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?= htmlspecialchars(ucwords($table)) ?> Table</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    
    <style>
        .scrollable-table {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="container mt-4">
    <h1 class="mb-4">Edit/View <?= htmlspecialchars(ucwords($table)) ?> Table</h1>
    <a href="../admin/dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>

    <div class="scrollable-table">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?= htmlspecialchars($column) ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <?php if ($column == 'ImageUrl' && $table == 'employees'): ?>
                                <td><img src="../../../Restaraunt_Management/<?= isset($row[$column]) ? htmlspecialchars($row[$column]) : 'default.jpg' ?>" class="rounded-circle" style="width: 100px; height: 100px;"</td>
                            <?php else: ?>
                                <td><?= isset($row[$column]) ? htmlspecialchars($row[$column]) : 'N/A' ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td>
                            <?php if ($table == 'employees'): ?>
                                <a href="employee_CRUD/employee_edit.php?id=<?= htmlspecialchars($row['EmployeeID']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php elseif ($table == 'food'): ?>
                                <a href="food_CRUD/food_edit.php?id=<?= htmlspecialchars($row['FoodID']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php elseif ($table == 'orders'): ?>
                                <a href="orders_CRUD/orders_edit.php?id=<?= htmlspecialchars($row['OrderID']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <?php if (!$row['Completed']): ?>
                                    <a href="orders_CRUD/order_completion_script.php?order_id=<?= htmlspecialchars($row['OrderID']) ?>&completed=1" class="btn btn-sm btn-success">Complete</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>



