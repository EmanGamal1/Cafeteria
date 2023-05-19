
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">


    <title>Login Page</title>
</head>
<body class="body">
<div class="container " >

  <div class="d-flex align-items-center justify-content-center" style="min-height: 75vh">

    <div class=" frame col-md-5">
        <h1 class="text-center text-light">Login</h1>
      <form method="POST" action="login.php">
        <div class="form-group">
          <label for="username">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>
          <div class="d-flex justify-content-around">
            <a href="resetPassword.php?action=reset" class="text-light">Forgot Password?</a>
            <button type="submit" class="btn btn-light" name="login">Submit</button>
          </div>
      </form>
        <?php
        require_once('FormValidation.php');
        ?>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>

