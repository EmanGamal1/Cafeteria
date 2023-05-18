<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once '../dbconfig.php';

require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

$db = connect_pdo();

if (isset($_POST['reset'])) {
    $email = $_POST['email'];

    // Generate a new password reset token
    $token = bin2hex(openssl_random_pseudo_bytes(32));

    // Save the token to the database
    $stmt = $db->prepare("UPDATE password_reset_tokens SET token = :token WHERE email = :email");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Send the password reset email
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.mailgun.org';
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@nader-mo.tech';
    $mail->Password = 'Aa012233#';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('salwa@iti.com', 'Salwa Rafik');
    $mail->addAddress($email);

    $mail->Subject = 'Password Reset Request';
    $mail->Body = 'Dear user,<br><br>'
        . 'We have received a request to reset your password. If you did not make this request, please ignore this email. '
        . 'Otherwise, please click the following link to reset your password:<br><br>'
        . '<a href="http://localhost/php/Cafeteria/Cafeteria/login/newPass.php' . $email . '&token=' . $token . '">Reset Password</a><br><br>'
        . 'Thank you,<br>'
        . 'Example.com';

    $mail->isHTML(true);

    if ($mail->send()) {
        echo 'Password reset email sent successfully.';
    } else {
        echo 'Error sending password reset email: ' . $mail->ErrorInfo;
    }
}