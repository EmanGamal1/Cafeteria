<?php
require_once '../dbconfig.php';

if ($_GET) {
  $errors = json_decode($_GET['errors']);
  $errors = (array) $errors;
}

?>

<!DOCTYPE html>
<html lang='en'>

<body>
  <?php require_once '../header.html'; ?>

  <div class='container'>
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <?php
          $db = connect_pdo();
          $query = 'SELECT * FROM `products`;';
          $stmt = $db->prepare($query);
          $res = $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $stmt->closeCursor();
          $product_id = $_GET['id'];

          foreach ($result as $product) {
            if ($product['product_id'] == $product_id) {
              echo "<h1>Update Product</h1>";
              echo "<form method='post' action='updatehandle.php?id={$product_id}' enctype='multipart/form-data'>";
              echo "<div class='form-group'>
                      <label for='product_id'>Product ID:</label>
                      <input type='text' name='product_id' id='product_id' disabled value='{$product['product_id']}' class='form-control' required>
                    </div>";

              echo "<div class='form-group'>
                      <label for='product_Name'>Product Name:</label>
                      <input type='text' name='product_Name' id='product_Name' value='{$product['product_Name']}' class='form-control' required>
                      <div class='text-danger'>" . (isset($errors['name']) ? $errors['name'] : "") . "</div>
                    </div>";

              echo "<div class='form-group'>
                      <label for='price'>Price:</label>
                      <input type='number' name='price' id='price' class='form-control' value='{$product['price']}' step='0.01' required>
                      <div class='text-danger'>" . (isset($errors['price']) ? $errors['price'] : "") . "</div>
                    </div>";

              echo "<div class='form-group'>
                      <label for='image'>Image:</label>
                      <input type='file' name='image' id='image' class='form-control' value='{$product['image']}' required>
                      <div class='text-danger'>" . (isset($errors['file']) ? $errors['file'] : "") . "</div>
                    </div>";
            }
          }
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
