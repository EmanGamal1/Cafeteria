<?php
require_once '../dbconfig.php';
$pdo = connect_pdo();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    // Prepare the SQL query to retrieve user orders and calculate the total price for each order
    $sql = "SELECT o.created_at, o.order_id, oi.quantity, p.price, oi.quantity * p.price AS total_price FROM orders o JOIN orders_items oi ON o.order_id = oi.order_id JOIN products p ON oi.product_id = p.product_id WHERE o.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);

    if ($stmt->rowCount() > 0) {
        echo '<div class="container mt-4">';
        echo '<table class="table table-hover">';
        echo '<thead class="thead-light">';
        echo '<tr>';
        echo '<th>Order Date</th>';
        echo '<th>Total Price</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row["created_at"] . "<span class='text-success ml-2 order-link' onclick='orderItemsFunn(this, " . $row['order_id'] . ")' style='font-size: 25px; cursor: pointer'>+</span></td>";
            echo "<td>" . $row["total_price"] . " EGP" . "</td>";
            echo "</tr>";
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '<div class="dev" style="display: none;" id="orderItems"></div>';
    } else {
        echo '<div class="container mt-4">';
        echo '<p class="mt-4">No orders found for user ID ' . $userId . '</p>';
        echo '</div>';
    }
}

$pdo = null;
?>

<script>
    function orderItemsFunn(link, orderId) {
        const dev = document.getElementById('orderItems');
        const allLinks = document.querySelectorAll('.order-link');

        if (link.textContent == "+") {
            // Show order items
            fetch(`../orders/getOrderItems.php?id=${orderId}`)
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