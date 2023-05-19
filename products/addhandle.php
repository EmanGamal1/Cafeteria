<?php
require_once '../dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $product_name = $_POST['name'];
    $price = $_POST['price'];
    $amount = $_POST['Amount'];
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];

    $errors = [];

    if (!isset($product_name) || empty($product_name)) {
        $errors['name'] = 'Name is required';
    } elseif (preg_match('/\d/', $product_name)) {
        $errors['name'] = 'Name cannot contain numbers';
    }

    if (!isset($price) || empty($price)) {
        $errors['price'] = 'Price is required';
    }

    if (!isset($amount) || empty($amount)) {
        $errors['Amount'] = 'Amount is required';
    }

    if (empty($file_name)) {
        $errors['file'] = 'Image is required';
    } else {
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['png', 'jpg', 'jpeg'];
        if (!in_array($extension, $allowed_extensions)) {
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

    if ($product) {
        // If the product already exists, show an error message
        $errors['name'] = 'Product with this name already exists';
        $form_errors = json_encode($errors);
        header("Location: productAdd.php?errors={$form_errors}");
        exit;
    } else {
        $stmt = $db->prepare("INSERT INTO products (product_Name, price, image, Amount) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product_name, $price, $file_name, $amount]);
        move_uploaded_file($file_tmp, "../images/products/{$file_name}");
        header("Location: productsList.php");
        exit;
    }
}
?>
