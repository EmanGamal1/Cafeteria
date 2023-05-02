<!DOCTYPE html>
<html lang="">
  <head>
    <title>Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  </head>
  <body>



    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="../home/homePage.php">Cafeteria</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="../home/homePage.php">Home</a>
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
            <a class="nav-link" href="#">Checks</a>
          </li>
        </ul>
      </div>
    </nav>
    
    <div class="container mt-4">
      <h1>Checks</h1>
      <?php
      include '../dbconfig.php';
      $db=connect_pdo();
      if (isset($_POST['deliver'])) {

        $orderId = $_POST['orderId'];

        // Update the order status to "delivered" in the database using a query
        $sql = "UPDATE orders SET status = 'Out for delivery' WHERE order_id = :orderId";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
      }

    //pagination
      $perPage = 5;
      $sql = "SELECT COUNT(*) as totalOrders FROM orders WHERE status IN ('Processing', 'Done')";
      $totalOrders = $db->query($sql)->fetch()['totalOrders'];
      $totalPages = ceil($totalOrders / $perPage);
      $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
      $offset = ($page - 1) * $perPage;


      // Retrieve the orders from the database with "processing" or "done" status
      $sql = "SELECT * FROM orders WHERE status IN ('Processing', 'Done')LIMIT $perPage OFFSET $offset";
      $orders = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

      // Display the orders in a table
      echo '<table class="table">';
      echo '<tr><th>Order Data</th><th>Name</th><th>Room</th><th>Ext</th><th>Action</th></tr>';
      foreach ($orders as $order) {
      $sql = "SELECT oi.quantity, p.product_Name, p.price FROM orders_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = :orderId";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':orderId', $order['order_id'], PDO::PARAM_INT);
      $stmt->execute();
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Calculate the total price for the products
  $totalPrice = 0;
  foreach ($products as $product) {
    $totalPrice += $product['price'] * $product['quantity'];
  }

  // Display the order in a row of the table
  echo '<tr>';
  echo '<td>' . $order['created_at'] . '</td>';
  echo '<td>' . $order['order_id'] . '</td>';
  echo '<td>' . $order['room'] . '</td>';
  echo '<td>' . $order['ext'] . '</td>';
  echo '<td><form method="post"><input type="hidden" name="orderId" value="' . $order['order_id'] . '"><input type="submit" name="deliver" value="Deliver"></form></td>';
  echo '</tr>';

  // Display the products for the order in a new row of the table
  echo '<tr><td></td><td colspan="4">';
  foreach ($products as $product) {
    echo $product['quantity'] . ' x ' . $product['product_Name'] . ' (' . $product['price'] . ' EGP)<br>';
  }
  echo '<strong>Total: ' . $totalPrice . ' EGP</strong>';
  echo '</td></tr>';

      }
      echo '</table>';
//      die(print_r($_POST));


      // Remove the delivered order from the list of orders
    foreach ($orders as $key => $order) {
        if (isset($orderId) && $order['order_id'] == $orderId) {
            unset($orders[$key]);
            break;
        }
    }


    //pagination
      echo '<nav aria-label="Page navigation">';
      echo '<ul class="pagination">';
      for ($i = 1; $i <= $totalPages; $i++) {
        echo '<li class="page-item' . ($i == $page ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
      }
      echo '</ul>';
      echo '</nav>';

    ?>
      </div>
    
    <footer class="bg-light text-center mt-4 p-3">
      <p>&copy; Cafeteria. All rights reserved.</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  </body>
</html>