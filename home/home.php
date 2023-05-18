<?php
include '../dbconfig.php';

$pdo = connect_pdo();

require_once ("orders.php");
require_once ("products.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $room = $_POST["room"];
    $ext = 2;
    $notes = $_POST["notes"];
    if ($_SESSION['role'] == 'user'){
        $user_id = $_SESSION['user_id'];
    }else if ($_SESSION['role'] == 'admin'){
        $user_id = $_POST["user-id"]; // Get user ID from dropdown list
    }
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    (function($) {
        $(document).ready(function() {
            // Handle search form submission
            $('#search-form').on('submit', function(event) {
                event.preventDefault(); // Prevent form submission

                var searchValue = $('#search-input').val(); // Get the search input value

                $.ajax({
                    url: 'products.php', // Replace with the actual path to your PHP script
                    type: 'GET',
                    data: {
                        search: searchValue
                    },
                    success: function(response) {
                        // Update the search results section with the response
                        $('#search-results').html(response);
                    }
                });
            });
        });
    })(jQuery);
</script>
