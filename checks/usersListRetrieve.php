<?php
require_once '../dbconfig.php';
$pdo = connect_pdo();

// Prepare the SQL query with a subquery to calculate the total order amount for each user
$sql = "SELECT u.name,u.id, (
    SELECT SUM(oi.quantity * p.price)
    FROM orders o
    JOIN orders_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE o.user_id = u.id
) AS total_amount
FROM users u
WHERE u.role = 'user'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo '<div class="container mt-4">';
    echo '<table class="table table-hover">';
    echo '<thead class="thead-light">';
    echo '<tr>';
    echo '<th>Name</th>';
    echo '<th>Total Amount</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    $totalPrice=0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $totalPrice += $row["total_amount"];
        echo "<tr>";
        echo "<td>" . $row["name"] . "<span class='text-success ml-2 user-link' onclick='userOrdersFun(this, " . $row['id'] . ")' style='font-size: 25px; cursor: pointer'>+</span> </td>";
        echo "<td>" . $row["total_amount"] . " EGP" . "</td>";
        echo "</tr>";
    }

    echo '</tbody>';
    echo '<tfoot>';
    echo '<tr style="background-color: #E9ECEF; font-weight: bold">';
    echo '<td>Total</td>';
    echo '<td>' . $totalPrice . ' EGP</td>';
    echo '</tr>';
    echo '</tfoot>';
    echo '</table>';
    echo '</div>';
}
echo '<div class="container mt-4" id="noParticularUser">';
echo 'yay';
echo '</div>';
echo '<div class="dev" style="display: none;" id="userOrders"></div>';

$pdo = null;
?>

<script>
    function userOrdersFun(link, userId) {
        const dev = document.getElementById('userOrders');
        const devNoUser = document.getElementById('noParticularUser');
        const allLinks = document.querySelectorAll('.user-link');

        if (link.textContent == "+") {
            // Show order items
            fetch(`getUserOrders.php?id=${userId}`)
                .then(response => response.text())
                .then(data => {
                    dev.innerHTML = data;
                    dev.style.display = "block";
                    devNoUser.style.display = "none";
                    link.innerHTML = '-';
                    allLinks.forEach(l => {
                        if (l != link) {
                            l.innerHTML = '+';
                        }
                    });
                })
                .catch(error => console.error(error));
        } else {
            fetch(`../orders/orderListRetrieve.php`)
                .then(response => response.text())
                .then(data => {
                    // Hide order items
                    devNoUser.innerHTML = data;
                    dev.style.display = "none";
                    devNoUser.style.display = "block";
                    link.innerHTML = '+';
                })
                .catch(error => console.error(error));

        }
    }

</script>
