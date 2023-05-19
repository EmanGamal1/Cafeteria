<?php
require_once '../dbconfig.php';
$db=connect_pdo();

<<<<<<< HEAD
$errors = array();

function validateLoginForm($email, $password) {
    global $errors;

    // Validate user input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Return true if there are no validation errors
    return (count($errors) == 0);
}

function getValidationErrors() {
    global $errors;

    // Return any validation errors
    return $errors;
}

// Handle form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (validateLoginForm($email, $password)) {
        // Check if the user exists in the database
        $query = "SELECT * FROM users WHERE email=:email AND password=:password";
        $stmt = $db->prepare($query);
        // Bind the parameters and execute the query
=======
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
>>>>>>> cc424336fc033447866775b9c57a7202d6c99791
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

<<<<<<< HEAD


        // Include the login form
        require_once '../login/login.php';


    // Display any validation errors
        if (count($errors) > 0) {
            echo "<div class='error-message mt-3  alert alert-danger'>";
            echo "<h5>Error:</h5>";
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            echo "</div>";
=======
    // Display any validation errors
    if (count($errors) > 0) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
>>>>>>> cc424336fc033447866775b9c57a7202d6c99791
        }
        echo "</ul>";
    }
}
?>