<?php
include '../dbconfig.php';

    if($_GET){
        //  var_dump($_GET);
        $errors = json_decode($_GET['errors']);
//        var_dump($errors); # object ---> casted to array
        $errors = (array) $errors;
        // var_dump($errors);
        
    }

?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Login form </title>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
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
    
<div class='container'>
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3">
    <?php
     
   $db =connect_pdo() ;
   $query = 'Select * from `products`;';
   $stmt = $db->prepare($query);
   $res = $stmt->execute();
   $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
   $stmt->closeCursor();
   $product_id = $_GET['id'];

    foreach($result as $product){
      if ($product['product_id']==$product_id){
        echo"<h1>Update Product</h1>";
        echo "<form method='post' action='updatehandle.php?id={$product_id}' enctype='multipart/form-data'>";
        echo "<div class='form-group'>
                <label for='product_id'>Product ID:</label>
                <input type='text' name='product_id' id='product_id' disabled value='{$product['product_id']}' class='form-control' required>
              </div>";
        
        echo "<div class='form-group'>
                <label for='product_Name'>Product Name:</label>
                <input type='text' name='product_Name' id='product_Name' value='{$product['product_Name']}' class='form-control' required>
              </div>";
        
        echo "<div class='form-group'>
                <label for='price'>Price:</label>
                <input type='number' name='price' id='price' class='form-control' value='{$product['price']}' step='0.01' required>
              </div>";
        
      
        echo "<div class='form-group'>
                <label for='image'>Image:</label>
                <input type='file' name='image' id='image' class='form-control' required>
            </div>";
      }}
        echo "<button type='submit' class='btn btn-primary'>Update Product</button>";
        echo "</form>";
        ?>
        
    </div>
  </div>
</div>


</div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' crossorigin='anonymous'></script>

</body>
</html>