<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<div class="container " >

    <div class="d-flex align-items-center justify-content-center" style="min-height: 75vh">
        <div class=" frame col-md-5">
            <h1 class="text-center text-light">New Password</h1>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once '../dbconfig.php';

// Connect to the database using PDO
try {
    $db = connect_pdo();
} catch (PDOException $e) {
    die("Failed to connect to the database: " . $e->getMessage());
}

// Check if the token is valid and not expired
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT * FROM password_reset_tokens WHERE token=:token AND expiry_time>:now";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->bindValue(':now', date('Y-m-d H:i:s'));
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);
        $email = $reset_request['email'];

        if (isset($_POST['submit'])) {
            $password = $_POST['password'];

            $confirm_password = $_POST['confirm-password'];

            // Validate the password and confirm password fields
            if ($password != $confirm_password) {
                $error = "Passwords do not match.";
            } else if (strlen($password) < 8) {
                $error = "Password must be at least 8 characters long.";
            } else {

                // Update the user's password in the database
                $query = "UPDATE users SET password=:password WHERE email=:email";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // Delete the reset request from the database
                $query = "DELETE FROM password_reset_tokens WHERE token=:token";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':token', $token);
                $stmt->execute();

                echo "<div class ='alert alert-success' role='alert'>Your password has been reset. <a class='btn btn-success' href='login.php'>Login</a> with your new password.</div>";
            }
        }

        // Display the form
        echo "<form action='newPass.php?token=$token' method='post'>";
        echo " <div class='form-group'><label for='password'>New password:</label><input class='form-control' type='password' name='password' id='password' required></div>";
        echo " <div class='form-group'><label for='confirm-password'>Confirm password:</label><input class='form-control' type='password' name='confirm-password' id='confirm-password' required></div>";

        if (isset($error)) {
            echo "<div class='error-message mt-3  alert alert-danger'>$error</div>";
        }
        echo "<button class='btn btn-light' type='submit' name='submit'>Reset password</button>";
        echo "</form>";
    } else {
        echo "<p>Invalid or expired token.</p>";
    }
} else {
    echo "<p>Token not provided.</p>";
}

?>
        </div>
    </div>
</div>

</body>
</html>