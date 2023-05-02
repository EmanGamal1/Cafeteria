<?php
require_once '../dbconfig.php';

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $pdo = connect_pdo();

    // Retrieve order items with product information and quantity
    $sql = "SELECT orders_items.product_id, products.product_name, products.price, products.image, COUNT(*) as quantity
            FROM orders_items 
            JOIN products ON orders_items.product_id = products.product_id 
            WHERE orders_items.order_id = ?
            GROUP BY orders_items.product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$orderId]);

    // Check if there are any order items
    if ($stmt->rowCount() > 0) {
        // Display order items as Bootstrap cards
        echo "<div class='container mt-4'>";
        echo "<div class='card-deck'>";
        while($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='card mb-4'>";
            echo "<div class='card-body'>";
            echo '<img width="150px" src="../images/products/' . $item["image"] . '" class="product-img" data-product-id="' . $item["product_id"] . '">';
            echo "<h5 class='card-title'>" . $item["product_name"] . "</h5>";
            echo "<p class='card-text'>" . $item["price"] . " EGP</p>";
            echo "<p class='card-text'> Quantity: " . $item["quantity"] . "</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>No items in this order</p>";
    }

    // Close the database connection
    $pdo = null;
} else {
    echo "<p>Order ID not specified</p>";
}
?>

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0px 0px 10px #ccc;
    }

    .product-img {
        border-radius: 10px;
        box-shadow: 0px 0px 10px #ccc;
        margin-bottom: 10px;
    }
</style>