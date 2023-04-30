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
            <a class="nav-link" href="../home/homePage.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Products</a>
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
      <h1>Products List</h1>
      <?php
// Connect to the database
$db = connect_pdo();

// Prepare a SQL query to select all data from a table
$sql = "SELECT * FROM products";
$stmt = $db->prepare($sql);

// Execute the query and fetch the data using fetchAll method
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through the data and display it
echo "<a  href='productAdd.php' class='btn btn-success me-2 ms-auto'>Add Product</a>";
echo "<table class='table table-striped'>";
echo "<tr><th>Product ID</th><th>Product_Name</th><th>Price</th><th>Image</th><th>Actions</th></tr>";
foreach ($data as $row) {
echo "<tr>";
          echo "<td>" . $row["product_id"] . "</td>";
          echo "<td>" . $row["product_Name"] . "</td>";
          echo "<td>" . $row["price"] . "</td>";
          echo "<td><img src='../images/{$row['image']}' alt='Product Image' width='100'></td>";
          $edit_url="productUpdate.php?id={$row['product_id']}&errors=";
          echo "<td>
          <button onclick='confirmDelete({$row['product_id']})' class='btn btn-danger'>Delete</button>
          <a  href='"."{$edit_url}' class='btn btn-success'>Edit</a>
          </td>";
echo "</tr>";
}
echo "</table>";
?>
      </div>
    
    <footer class="bg-light text-center mt-4 p-3">
      <p>&copy; Cafeteria. All rights reserved.</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
      function confirmDelete(productId) {
    if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = `deletehandle.php?id=${productId}`;
           }
         }
    </script>
  </body>
</html>