<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'dbconfig.php';

require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';

$db = connect_pdo();

// Check if the email and token are present in the query string
if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Check if the token is valid
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND reset_token = :token");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Invalid token or email address
        header('Location: login.php');
        exit;
    }
} else {
    // Email and/or token not present in the query string
    header('Location: login.php');
    exit;
}

// Handle the password reset form submission
if (isset($_POST['reset'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update the user's password and reset token in the database
        $stmt = $db->prepare("UPDATE users SET password = :password, reset_token = NULL WHERE email = :email");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        echo 'Password reset successfully.';

        // Redirect the user to the login page
        header('Location: login.php');
        exit;
    } else {
        echo 'Passwords do not match.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
<h1>Password Reset</h1>
<form method="post">
    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <br>
    <button type="submit" name="reset">Reset Password</button>
</form>
</body>
</html>
