<?php
require_once './home.php';

$pdo = connect_pdo();
$stmt = $pdo->query("SELECT * FROM orders ORDER BY order_id DESC LIMIT 1");
$order = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT * FROM orders_items WHERE order_id = ?");
$stmt->execute([$order['order_id']]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($stmt->rowCount() > 0) {
    echo '<div class="container">';
    $order_summary = '<p></p>';
//    if ($_SESSION['role'] == 'user') {
//    echo '<style>.users { display: none; }</style>';

    echo '<h4 class="text-center" style="display: flex; align-items: center;"><hr style="flex: 1;"><span class="mx-5"> Latest Order </span><hr style="flex: 1;"></h4><br>';
    echo '<div class="row">';
    foreach ($order_items as $item) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$item['product_id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $order_summary .= '<div class="col-md-3 mb-3">';
        $order_summary .= '<div class="text-center">';
        $order_summary .= '<img width="150px" src="../images/products/' . $product["image"] . '" class="product-img" data-product-id="' . $product["product_id"] . '">';
        $order_summary .= '<p>' . $item['quantity'] . " " . $product['product_Name'] . '</p>';
        $order_summary .= '</div></div>';
    }
    echo $order_summary;
    echo '</div>';
//    } else if ($_SESSION['role'] == 'admin') {
    // Show dropdown list of users
    $stmt = $pdo->query("SELECT id, name FROM users where role='user'");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<style>.users { display: flex; }</style>';
//    }
    echo '</div>';
}
