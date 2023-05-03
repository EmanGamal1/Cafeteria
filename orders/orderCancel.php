<?php
require_once '../dbconfig.php';
$pdo = connect_pdo();

// Retrieve the record ID from the form data
$id = $_POST['id'];

// Check the status of the order
$sql = "SELECT status FROM orders WHERE order_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    $status = $order['status'];
} else {
    echo "Invalid order ID";
    exit();
}

if ($status == 'Processing') {
    // Delete the record
    $sql = "DELETE FROM orders WHERE order_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    // Redirect to orderList.php
    header('Location: ordersList.php');
}

// Close the database connection
$pdo = null;
?>