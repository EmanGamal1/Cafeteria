
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
            <h1 class="text-center text-light">Reset Password</h1>
        <form method="post"  >
            <div class="form-group">
                <label for="email">Enter your email address:</label>
                <input  class="form-control" type="email" id="email" name="email" placeholder="Enter Your Email">
            </div>

            <button class="btn btn-light mb-3" type="submit" name="reset">Send</button>
        </form>
            <?php
            require_once('resetPasswordVal.php');
            ?>
        </div>
    </div>
</div>
</body>
</html>