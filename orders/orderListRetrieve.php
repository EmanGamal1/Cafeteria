<?php
require_once '../dbconfig.php';
$pdo = connect_pdo();

$dateFrom = isset($_POST['date_from']) ? $_POST['date_from'] : null;
$dateTo = isset($_POST['date_to']) ? $_POST['date_to'] : null;

$sql = "SELECT orders.*, SUM(orders_items.quantity * products.price) AS total_price 
        FROM orders 
        JOIN orders_items ON orders.order_id = orders_items.order_id 
        JOIN products ON orders_items.product_id = products.product_id";
if ($dateFrom && $dateTo) {
    $sql .= " WHERE orders.created_at BETWEEN :dateFrom AND :dateTo";
}
$sql .= " GROUP BY orders.order_id";

$stmt = $pdo->prepare($sql);
if ($dateFrom && $dateTo) {
    $stmt->execute(['dateFrom' => $dateFrom, 'dateTo' => $dateTo]);
} else {
    $stmt->execute();
}

$totalPrice = 0;

if ($stmt->rowCount() > 0) {
    echo '<div class="container mt-4">';
    echo '<form class="form-inline mb-2" method="post">';
    echo '<div class="form-group mr-2">';
    echo '<label for="date_from" class="mr-2">From:</label>';
    echo '<input type="date" name="date_from" class="form-control" id="date_from" value="' . $dateFrom . '">';
    echo '</div>';
    echo '<div class="form-group mr-2">';
    echo '<label for="date_to" class="mr-2">To:</label>';
    echo '<input type="date" name="date_to" class="form-control" id="date_to" value="' . $dateTo . '">';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary" name="submit">Filter</button>';
    echo '</form>';

    echo '<div class="table-responsive">';
    echo '<table class="table table-hover">';
    echo '<thead class="thead-light">';
    echo '<tr>';
    echo '<th>Order Date</th>';
    echo '<th>Status</th>';
    echo '<th>Total Price</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $totalPrice += $order["total_price"];
        $date = date("Y/m/d h:i A", strtotime($order["created_at"]));
        echo "<tr>";
        echo "<td>" . $date . "<span class='text-success ml-2 order-link' onclick='orderItemsFun(this, " . $order['order_id'] . ")' style='font-size: 25px; cursor: pointer'>+</span></td>";        echo "<td>" . $order["status"] . "</td>";
        echo "<td>" . $order["total_price"] . " EGP" . "</td>";
        echo "<td>";
        if ($order["status"] == "Processing") {
            echo "<form method='post' action='orderCancel.php'> <input type='hidden' name='id' value='" . $order['order_id'] . "'> <button type='submit' class='btn btn-danger' name='submit'>Cancel</button> </form>";
        } else {
            echo "<button type='button' class='btn btn-danger' disabled>Cancel</button>";
        }
        echo "</td>";
        echo "</tr>";
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    echo '<div class="dev" style="display: none;" id="orderItems"></div>';
    echo '<p class="mt-4">Total EGP ' . $totalPrice . '</p>';
    echo '</div>';
} else {
    echo '<div class="container mt-4">';
    echo '<form class="form-inline mb-2" method="post">';
    echo '<div class="form-group mr-2">';
    echo '<label for="date_from" class="mr-2">From:</label>';
    echo '<input type="date" name="date_from" class="form-control" id="date_from">';
    echo '</div>';
    echo '<div class="form-group mr-2">';
    echo '<label for="date_to" class="mr-2">To:</label>';
    echo '<input type="date" name="date_to" class="form-control" id="date_to">';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary" name="submit">Filter</button>';
    echo '</form>';
    echo '<p class="mt-4">No orders</p>';
    echo '</div>';
}

$pdo = null;
?>

<script>
    function orderItemsFun(link, orderId) {
        const dev = document.getElementById('orderItems');
        const allLinks = document.querySelectorAll('.order-link');

        if (link.textContent == "+") {
            // Show order items
            fetch(`getOrderItems.php?id=${orderId}`)
                .then(response => response.text())
                .then(data => {
                    dev.innerHTML = data;
                    dev.style.display = "block";
                    link.innerHTML = '-';
                    allLinks.forEach(l => {
                        if (l != link) {
                            l.innerHTML = '+';
                        }
                    });
                })
                .catch(error => console.error(error));
        } else {
            // Hide order items
            dev.style.display = "none";
            link.innerHTML = '+';
        }
    }

</script>