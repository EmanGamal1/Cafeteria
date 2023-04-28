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
  $sql = "SELECT * FROM orders";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr><td>Name: " . $row["name"] . "</td></tr>";
    }
    echo "</table>";
  } else {
    echo "0 results";
  }
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
      <h1>Products List</h1>
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