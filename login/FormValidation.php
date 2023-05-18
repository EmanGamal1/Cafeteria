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
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $_SESSION['token'] = $token;

                // Get the user's information from the database
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Store the user's information in the session
                $_SESSION['user_id'] = $user['id'];

                // Redirect the user to their dashboard
                header('location: ../home/homePage.php');
                exit();

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