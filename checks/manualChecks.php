
<!DOCTYPE html>
<html lang="">
<head>
    <title>Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>



<?php
require_once '../header.html';
?>

<div class="container mt-4">
    <h1>Checks</h1>
    <?php
    require_once '../dbconfig.php';
    $db = connect_pdo();
    if (isset($_SESSION['token'])) {
        $stmt = $db->prepare("SELECT role FROM users WHERE token = ?");
        $stmt->execute([$_SESSION['token']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    if ($user && $user['role'] == 'admin') {
    // Handle delivery of orders
    if (isset($_POST['deliver'])) {
        $orderId = $_POST['orderId'];
        $sql = "UPDATE orders SET status = 'Out for delivery' WHERE order_id = :orderId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Pagination setup
    $perPage = 5;
    $sql = "SELECT COUNT(*) as totalOrders FROM orders WHERE status IN ('Processing', 'Done')";
    $totalOrders = $db->query($sql)->fetchColumn();
    $totalPages = ceil($totalOrders / $perPage);
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $perPage;

    // Retrieve orders and display them in a table
    $sql = "SELECT * FROM orders WHERE status IN ('Processing', 'Done') LIMIT :perPage OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<table class="table">';
    echo '<tr><th>Order Data</th><th>ID</th><th>Room</th><th>Ext</th><th>Action</th></tr>';

    foreach ($orders as $order) {
        // Retrieve order items and calculate total price
        $sql = "SELECT oi.quantity, p.product_Name, p.price ,p.image FROM orders_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = :orderId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':orderId', $order['order_id'], PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }

        // Display order in table
        echo '<tr id="order_' . $order['order_id'] . '" onclick="displayOrder(' . $order['order_id'] . ')">';
        echo '<td>' . $order['created_at'] . '</td>';
        echo '<td>' . $order['order_id'] . '</td>';
        echo '<td>' . $order['room'] . '</td>';
        echo '<td>' . $order['ext'] . '</td>';
        echo '<td><form method="post"><input type="hidden" name="orderId" value="' . $order['order_id'] . '"><input type="submit" name="deliver" value="Deliver"></form></td>';
        echo '</tr>';
        echo '<tr id="details_row_' . $order['order_id'] . '" style="display: none;"><td></td><td colspan="4">';
        foreach ($products as $product) {
            echo"<div class='d-inline justify-content-between'><img src='../images/products/{$product['image']}'alt='Product Image' width='100' height='100' ></div>";
            echo"<div class='d-inline justify-content-between'>".$product['quantity'] . ' x ' . $product['product_Name'] . ' (' . $product['price'] . ' EGP)'."</div>";
        }
        echo '<br><strong>Total: ' . $totalPrice . ' EGP</strong>';
        echo '</td></tr>';
    }

    echo '</table>';
    }
    // Remove delivered order from list of orders
    if (isset($orderId)) {
        foreach ($orders as $key => $order) {
            if ($order['order_id'] == $orderId) {
                unset($orders[$key]);
                break;
            }
        }
    }

    // Display pagination links
    echo '<nav aria-label="Page navigation">';
    echo '<ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = ($i == $page) ? ' active' : '';
        echo '<li class="page-item' . $activeClass . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
    echo '</ul>';
    echo '</nav>';
    ?>
</div>


<?php
require_once '../footer.html';
?>
<script>
    function displayOrder(orderId) {
        $('#details_row_' + orderId).toggle();
    }
</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>