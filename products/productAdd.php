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
<body>

<?php
require_once '../header.html';
?>
    
   <h2 class='text-center'> Add product </h2>
    <div class="container">
    <form method="POST"  action="addhandle.php" enctype="multipart/form-data">
    <div class="form-group">
        <label for="formGroupExampleInput">Product Name:</label>
        <input type="text" name="name" class="form-control" id="formGroupExampleInput">
        <div class="text-danger"><?php if(isset($errors['name'])) echo $errors['name']; ?></div>
    </div>
    <br>
    <div class="form-group">
        <label for="formGroupExampleInput">Price:</label>
        <input type="number" name="price" class="form-control" id="formGroupExampleInput">
        <div class="text-danger"><?php if(isset($errors['price'])) echo $errors['price']; ?></div>
    </div>
    <br>
    <div class="form-group">
        <label for="formGroupExampleInput">Image Upload:</label>
        <input type="file" name="file" class="form-control-file" id="formGroupExampleInput">
        <div class="text-danger"><?php if(isset($errors['file'])) echo $errors['file']; ?></div>
    </div>
    <div class="form-group">
        <label for="formGroupExampleInput">Amount:</label>
        <input type="number" name="Amount" class="form-control" id="formGroupExampleInput">
        <div class="text-danger"><?php if(isset($errors['Amount'])) echo $errors['Amount']; ?></div>
    </div>
    <br>
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

