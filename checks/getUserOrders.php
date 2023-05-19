<?php
// Include the database configuration file
require_once '../dbconfig.php';

// Connect to the database
$pdo = connect_pdo();

// Check if the user ID is set in the query string
if (isset($_GET['id'])) {
    // Get the user ID from the query string
    $userId = $_GET['id'];

    // Prepare the SQL query to retrieve the user's orders and calculate the total price for each order
    $sql = "SELECT o.created_at, o.order_id, oi.quantity, p.price, oi.quantity * p.price AS total_price FROM orders o JOIN orders_items oi ON o.order_id = oi.order_id JOIN products p ON oi.product_id = p.product_id WHERE o.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);

    // Check if the query returned any rows
    if ($stmt->rowCount() > 0) {
        // Display the table header
        echo '<div class="container mt-4">';
        echo '<table class="table table-hover">';
        echo '<thead class="thead-light">';
        echo '<tr>';
        echo '<th>Order Date</th>';
        echo '<th>Total Price</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Loop through each row returned bythe query and display the order details in a table row
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row["created_at"] . "<span class='text-success ml-2 order-link' onclick='orderItemsFunn(this, " . $row['order_id'] . ")' style='font-size: 25px; cursor: pointer'>+</span></td>";
            echo "<td>" . $row["total_price"] . " EGP" . "</td>";
            echo "</tr>";
        }

        // Display the table footer
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '<div class="dev" style="display: none;" id="orderItems"></div>';
    } else {
        // Display a message if there are no orders for the user
        echo '<div class="container mt-4">';
        echo '<p class="mt-4">No orders found for user ID ' . $userId . '</p>';
        echo '</div>';
    }
}

// Close the database connection
$pdo = null;
?>

<script>
    // Define the function that retrieves and displays the order items
    function orderItemsFunn(link, orderId) {
        const dev = document.getElementById('orderItems');
        const allLinks = document.querySelectorAll('.order-link');

        if (link.textContent == "+") {
            // Show order items
            // Send an AJAX request to retrieve the order items for the specified order ID
            fetch(`../orders/getOrderItems.php?id=${orderId}`)
                .then(response => response.text())
                .then(data => {
                    // Display the order items in the hidden div element
                    dev.innerHTML = data;
                    dev.style.display = "block";
                    link.innerHTML = '-';
                    // Change all the other links to the '+' symbol to ensure only one set of order details is expanded
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