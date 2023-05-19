<?php
// Include the database configuration file
require_once '../dbconfig.php';

// Connect to the database
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

// Execute the query
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Check if the query returned any rows
if ($stmt->rowCount() > 0) {
    // Display the table header
    echo '<div class="container mt-4">';
    echo '<table class="table table-hover">';
    echo '<thead class="thead-light">';
    echo '<tr>';
    echo '<th>Name</th>';
    echo '<th>Total Amount</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop through each row returned by the query and display the user and their total order amount in a table row
    $totalPrice=0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $// Keep track of the total order amount for all users
        $totalPrice += $row["total_amount"];

        // Display the user and their total order amount in a table row, and include a clickable link to view their orders
        echo "<tr>";
        echo "<td>" . $row["name"] . "<span class='text-success ml-2 user-link' onclick='userOrdersFun(this, " . $row['id'] . ")' style='font-size: 25px; cursor: pointer'>+</span> </td>";
        echo "<td>" . $row["total_amount"] . " EGP" . "</td>";
        echo "</tr>";
    }

    // Display the table footer with the total order amount for all users
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

// Display an empty div element to use for displaying user orders
echo '<div class="container mt-4" id="noParticularUser">';
echo '</div>';
echo '<div class="dev" style="display: none;" id="userOrders"></div>';

// Close the database connection
$pdo = null;
?>

<script>
    // Define the function that retrieves and displays user orders
    function userOrdersFun(link, userId) {
        const dev = document.getElementById('userOrders');
        const devNoUser = document.getElementById('noParticularUser');
        const allLinks = document.querySelectorAll('.user-link');

        if (link.textContent == "+") {
            // Show the user's orders
            fetch(`getUserOrders.php?id=${userId}`)
                .then(response => response.text())
                .then(data => {
                    // Display the user's orders in the hidden div element and hide the "no particular user selected" message
                    dev.innerHTML = data;
                    dev.style.display = "block";
                    devNoUser.style.display = "none";
                    // Change all the other links to the '+' symbol to ensure only one set of user orders is expanded
                    allLinks.forEach(l => {
                        if (l != link) {
                            l.innerHTML = '+';
                        }
                    });
                    // Change the clicked link to the '-' symbol
                    link.innerHTML = '-';
                })
                .catch(error => console.error(error));
        } else {
            // Hide the user's orders and display the "no particular user selected" message
            fetch(`../orders/orderListRetrieve.php`)
                .then(response => response.text())
                .then(data => {
                    devNoUser.innerHTML = data;
                    dev.style.display = "none";
                    devNoUser.style.display = "block";
                    // Change the clicked link back to the '+' symbol                    link.innerHTML = '+';
                })
                .catch(error => console.error(error));
        }
    }
</script>