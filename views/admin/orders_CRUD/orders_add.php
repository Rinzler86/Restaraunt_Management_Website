<?php
require_once '../../../core/Database.php';
require_once '../../../models/OrderModel.php';
require_once '../../../models/EmployeeModel.php';
require_once '../../../models/TableModel.php';

$database = new Database();
$db = $database->getConnection();

$orderModel = new OrderModel($db);
$employeeModel = new EmployeeModel($db);
$tableModel = new TableModel($db);

$employees = $employeeModel->getAll();
$tables = $tableModel->getAllTables();

foreach ($tables as $key => $table) {
    $tables[$key]['Available'] = $tableModel->getTableStatus($table['TableID']);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tableId = $_POST['table_id'] ?? null;
    $employeeId = $_POST['employee_id'] ?? null;
    $dineIn = 1; // Dine-In implied

    if ($orderModel->addOrder($tableId, $employeeId, $dineIn)) {
        $message = "Order added successfully.";
        header("Refresh:1");
    } else {
        $message = "Error adding order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Order</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/styles.css">
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .restaurant-layout {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .table-rep {
            width: 100px;
            height: 100px;
            border: 2px solid black;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px auto;
        }
        .available { background-color: lightgreen; }
        .unavailable { background-color: red; }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Add Order</h1>
        <p><a href="../../admin/dashboard.php" class="btn btn-secondary">Back to Dashboard</a></p>
        <?php if ($message): ?>
            <p class="alert alert-info"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <div class="restaurant-layout">
            <?php foreach ($tables as $table): ?>
                <div class="table-rep <?= $table['Available'] ? 'unavailable' : 'available' ?>">
                    Table <?= htmlspecialchars($table['TableID']) ?>
                </div>
            <?php endforeach; ?>
        </div>
            
        <form action="orders_add.php" method="post" id="addOrderForm" class="mt-4">
            <div class="form-group">
                <label for="table_id">Select a Table:</label>
                <select name="table_id" id="table_id" class="form-control" required>
                    <option value="">Select a Table</option>
                    <?php foreach ($tables as $table): ?>
                        <option value="<?= htmlspecialchars($table['TableID']) ?>">Table <?= htmlspecialchars($table['TableID']) ?> (Capacity: <?= htmlspecialchars($table['Capacity']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="employee_id">Select an Waiter/Waitress:</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value="">Select an Employee</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['EmployeeID']) ?>"><?= htmlspecialchars($employee['FirstName'] . ' ' . $employee['LastName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Order</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <script>
        document.getElementById('addOrderForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submit action

            var formData = new FormData(this);

            fetch('orders_add.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                setTimeout(function() {
                    location.reload(); // Reload the page after 1 second
                }, 1000);
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    
</body>
</html>



