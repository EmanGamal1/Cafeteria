<?php

    if($_GET){
        // var_dump($_GET);
        $errors = json_decode($_GET['errors']);
//        var_dump($errors); # object ---> casted to array
        $errors = (array) $errors;
        // var_dump($errors);
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
    
   <h2 class='text-center'> Add product </h2>
    <div class="container">
    <form method="POST" class="position-absolute top-50 start-50 translate-middle" style="width:450px" action="addhandle.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="formGroupExampleInput">product_Name</label>
                <input type="text" name="name" class="form-control " id="formGroupExampleInput">
                <div class="text-danger"> <?php  if(isset($errors['name']))  echo $errors['name']; ?></div>
            </div><br>
            <div class="form-group">
                <label for="formGroupExampleInput">price</label>
                <input type="number" name="price" class="form-control" id="formGroupExampleInput">
                <div class="text-danger"> <?php  if(isset($errors['price']))  echo $errors['price']; ?></div>
            </div><br>
          
            <div class="form-group">
                <label for="formGroupExampleInput">Image Upload</label>
                <input type="file" name="file" class="form-control" id="formGroupExampleInput">
                <div class="text-danger"> <?php  if(isset($errors['file']))  echo $errors['file']; ?></div>
            </div><br>
        <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
              <button type="reset" class="btn btn-success">Reset</button>    
        </div>
          
          </form>
    </div>


 
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  </body>
</html>

