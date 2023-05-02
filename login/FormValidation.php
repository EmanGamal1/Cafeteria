<?php
include '../dbconfig.php';
$db=connect_pdo();
// Start a session to store user information
session_start();

// Connect to the database using PDO


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
            // Get the user's information from the database
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Store the user's information in the session
            $_SESSION['user'] = $user;

            // Redirect the user to their dashboard
            if ($user['role'] == 'admin') {
                header('location: ../home/homePage.php');
            } else {
                header('location: ../home/home.php');
            }
        } else {
            // Display an error message
            $errors[] = "Invalid email or password";
        }
    }

    // Display any validation errors
    if (count($errors) > 0) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}

?>