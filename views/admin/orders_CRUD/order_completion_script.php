<?php
require_once '../../../core/Database.php';
require_once '../../../models/OrderModel.php';

$database = new Database();
$db = $database->getConnection();
$orderModel = new OrderModel($db);

if (isset($_GET['order_id']) && isset($_GET['completed'])) {
    $orderId = $_GET['order_id'];
    $completed = $_GET['completed'];

    if ($orderModel->updateOrder($orderId, null, null, null, $completed)) {
        // Redirect back to the table view
        header('Location: ../view_edit_table.php?table=orders');
        exit;
    } else {
        echo "Error completing order.";
    }
}


