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
        $email= $reset_request['email'];

        if (isset($_POST['submit'])) {
            $password = $_POST['password'];


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
