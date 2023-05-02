<<<<<<< HEAD
<?php
include '../dbconfig.php';

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

    echo '<h4 class="text-center">Latest Order</h4><br>';
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
    echo '<hr>';
}

$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    echo '<h4 class="text-center">Products</h4><br>';
    echo '<div class="row">';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="col-md-3 mb-3">';
        echo '<div class="text-center">';
        echo '<img width="150px" src="../images/products/' . $row["image"] . '" class="product-img" data-product-id="' . $row["product_id"] . '">';
        $product_Name = "product_Name";
        $product_id = "product_id";
        echo '<br>' . '<p class="product-name">' . $row["{$product_Name}"] . '</p>';
        echo '<p class="price">' . $row["price"] . '<span> EGP</span>' . '</p>';
        echo '</div></div>';
        echo '<div id="added-product-name"></div>';
    }
    echo '</div>';
} else {
    echo "0 results";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $room = $_POST["room"];
    $ext = 2;
    $notes = $_POST["notes"];
//    if ($_SESSION['role'] == 'user'){
//        $user_id = $_SESSION['user_id'];
//    }else if ($_SESSION['role'] == 'admin'){
        $user_id = $_POST["user-id"]; // Get user ID from dropdown list
//    }
    $product_id = $_POST["productIDs"];
    $product_id_decoded = json_decode($product_id);

    // Insert order into orders table
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, room, ext, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $room, $ext, $notes]); // Insert the selected user ID into the user_id column

    $order_id = $pdo->lastInsertId(); // Get ID of inserted order

    // Insert order items into orders_items table
    $quantity = 1;
    foreach ($product_id_decoded as $product_id) {
        // Check if the product ID already exists in the order
        $stmt = $pdo->prepare("SELECT * FROM orders_items WHERE order_id = ? AND product_id = ?");
        $stmt->execute([$order_id, $product_id]);
        $existing_item = $stmt->fetch();

        if ($existing_item) {
            // If the product ID already exists, increment the quantity
            $new_quantity = $existing_item['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE orders_items SET quantity = ? WHERE product_id = ?");
            $stmt->execute([$new_quantity, $existing_item['product_id']]);
        } else {
            // If the product ID does not exist, insert a new row
            $stmt = $pdo->prepare("INSERT INTO orders_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $quantity]);
        }
    }
}
$pdo = null;
?>
=======
<!DOCTYPE html>
<html>
<head>
    <title>Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<?php
include '../dbconfig.php';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../home/homePage.php">Cafeteria</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../products/productsList.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../users/usersList.php">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../orders/manualOrder.php">Manual Order</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../checks/checksList.php">Checks</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h1>Home</h1>
    <h1>user</h1>
</div>

<footer class="bg-light text-center mt-4 p-3">
    <p>&copy; Cafeteria. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<?php
mysqli_close($conn);
?>
</body>
</html>
>>>>>>> origin/Login
