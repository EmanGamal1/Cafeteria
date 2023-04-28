
<?php

    if($_GET){
        var_dump($_GET);
        $errors = json_decode($_GET['errors']);
//        var_dump($errors); # object ---> casted to array
        $errors = (array) $errors;
        var_dump($errors);
    }

?>

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
<div class="container">
<h2>Add Product</h2>
<form method="post" action="add.php">
  <div class="form-group">
    <label for="input1">Product_name:</label>
    <input type="text" class="form-control" id="input1" placeholder="Enter Product_name...">
  </div>
  <div class="form-group">
    <label for="input2">Price :</label>
    <input type="number" class="form-control" id="input2" placeholder="Enter Price....">
  </div>
  <div class="form-group">
  <label for="formFile" class="form-label">Image</label>
  <div class="input-group mb-3">
  <label class="input-group-text" for="inputGroupFile01">Upload</label>
  <input name="file" type="file" class="form-control" id="inputGroupFile01">
  </div>
   </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
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