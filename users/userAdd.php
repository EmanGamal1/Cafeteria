<?php

    
    if($_GET){
        $errors = json_decode($_GET['errors']);
        $errors = (array) $errors;
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add user</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../home/homePage.php">Cafeteria</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <a class="nav-link" href="#">Users</a>
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

        <form class="form-group" action="userValidation.php" method="POST" enctype="multipart/form-data">
            <div class="">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="text" class="form-control form-control-sm" name="Name">
                <div class="text-danger"> <?php  if(isset($errors['Name']))  echo $errors['Name']; ?></div>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control form-control-sm" aria-describedby="emailHelp" name="email">
                <div class="text-danger"> <?php  if(isset($errors['email']))  echo $errors['email']; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control form-control-sm" id="exampleInputPassword1" name="password">
                <div class="text-danger"> <?php  if(isset($errors['password']))  echo $errors['password']; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                <input type="password" class="form-control form-control-sm" id="exampleInputPassword1"
                    name="confirm-password">
                <div class="text-danger"> <?php  if(isset($errors['confirmed']))  echo $errors['confirmed']; ?>
                </div>

            </div>
            <div class="mb-3">
                <label for="room">Room No</label>
                <input type="number" class="form-control" id="room" name="room" min="2000" max="4000" step="1">
                <div class="text-danger"> <?php  if(isset($errors['room']))  echo $errors['room']; ?>

                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Ext</label>
                    <input type="number" min="500" max="600" step="1" class="form-control form-control-sm"
                        id="exampleInputPassword1" name="ext">
                    <div class="text-danger"> <?php  if(isset($errors['ext']))  echo $errors['ext']; ?>

                    </div>
                    <div class="form-group">
                        <label for="image">image</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Image</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="file">
                                <label class="custom-file-label" for="image">Choose Image</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Submit</button>
                    <button type="reset" class="btn btn-warning">Reset</button>




        </form>
    </div>
    </div>