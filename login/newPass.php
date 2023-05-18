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
    $query = "SELECT * FROM password_reset_tokens WHERE token=:token AND expires_at>:now";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->bindValue(':now', date('Y-m-d H:i:s'));
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $reset_request['user_id'];

        if (isset($_POST['submit'])) {
            $password = $_POST['password'];
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $query = "UPDATE users SET password=:password WHERE id=:user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            // Delete the reset request from the database
            $query = "DELETE FROM password_reset_tokens WHERE token=:token";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            echo "Your password has been reset. <a href=\"login.php\">Login</a> with your new password.";
        } else {
            echo "<form action=\"newPass.php?token=$token\" method=\"post\">";
            echo "<label for=\"password\">New password:</label>";
            echo "<input type=\"password\" name=\"password\" id=\"password\" required>";
            echo "<button type=\"submit\" name=\"submit\">Reset password</button>";
            echo "</form>";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "Token not provided.";
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
