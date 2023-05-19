<?php
require_once '../dbconfig.php';
$db=connect_pdo();

// Handle form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    $errors = array();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Check if there are any validation errors
    if (count($errors) == 0) {
        // Check if the user exists in the database
        $query = "SELECT * FROM users WHERE email=:email AND password=:password";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // Start a session to store user information and generate a token
            session_start();
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            $_SESSION['token'] = $token;
            $query = "UPDATE users SET token=:token WHERE email=:email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Get the user's information from the database
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Store the user's information in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect the user to their dashboard
            header('location: ../home/homePage.php');
            exit();

        } else {
            // Display an error message
            $errors[] = "Invalid email or password";
        }
    }
}

<<<<<<< HEAD
// Include the login form
require_once 'login.php';

// Display any validation errors
if (count($errors) > 0) {
    echo "<div class='alert alert-danger mt-3'>";
    echo "<h5>Error:</h5>";
    echo "<ul class='mb-0'>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
=======
    // Display any validation errors
    if (count($errors) > 0) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
>>>>>>> c99ae1fa4d4e35a70919d590d7ee12217a68dde9
    }
    echo "</ul>";
    echo "</div>";
}
?>