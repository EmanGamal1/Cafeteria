<?php
require_once '../dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $product_name = $_POST['name'];
    $price = $_POST['price'];
    $Amount = $_POST['Amount'];
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];

    $errors = array();
    if (!isset($product_name) or empty($product_name)) {
        $errors['name'] = 'Name is required';
    }
    if (!isset($price) or empty($price)) {
        $errors['price'] = 'Price is required';
    }
    if (!isset($Amount) or empty($Amount)) {
        $errors['Amount'] = 'Amount is required';
    }
    if (empty($file_name)) {
        $errors['file'] = 'Image is required';
    } else {
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_extenstions = array('png', 'jpg', 'jpeg');
        if (!in_array($extension, $allowed_extenstions)) {
            $errors['file'] = 'Invalid file extension';
        }
        if ($file_size > 1000000) {
            $errors['file'] = 'File size must be less than 1MB';
        }
    }

    if (!empty($errors)) {
        $form_errors = json_encode($errors);
        header("Location: productAdd.php?errors={$form_errors}");
        exit;
    }

    $db = connect_pdo();
    $query = "SELECT * FROM products WHERE product_Name = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$product_name]);
    $product = $stmt->fetch();
    if($product) {
        // If the product already exists, show an error message
        echo "Product already exists";
    } else {
        $stmt = $db->prepare("INSERT INTO products (product_Name, price, image,Amount) VALUES (?, ?, ?,?)");
        $stmt->execute(array($product_name, $price, $file_name,$Amount));
        move_uploaded_file($file_tmp, "../images/products/{$file_name}");
        header("Location: productsList.php");
        exit;
    } 
}
