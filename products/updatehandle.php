<?php

// Connect to the database
require_once '../dbconfig.php';

$db = connect_pdo();

$errors = []; // Array to store validation errors

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data
  $product_id = $_GET['id'];
  $product_name = $_POST['product_Name'];
  $price = $_POST['price'];
  $image = $_FILES['image'];

  // Validate product name
  if (preg_match('/\d/', $product_name)) {
    $errors['name'] = 'Name cannot contain numbers';
  }

  // Validate price
  if (!is_numeric($price) || $price <= 0) {
    $errors['price'] = 'Price must be a positive number';
  }

  // Validate image file
  if (!empty($image['name'])) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_extensions)) {
      $errors['file'] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
    }
  }

  if (!empty($errors)) {
    $form_errors = json_encode($errors);
    header("Location: productUpdate.php?id={$product_id}&errors={$form_errors}");
    exit();
  }
  
  // If there are no validation errors, proceed with updating the product

  // Handle image upload
  if (!empty($image['name'])) {
    $filename = uniqid() . '.' . $ext;
    $path = '../images/products/' . $filename;
    move_uploaded_file($image['tmp_name'], $path);
  }

  // Update product in the database
  $stmt = $db->prepare("UPDATE products SET product_Name = ?, price = ?, image = ? WHERE product_id = ?");
  $stmt->execute([$product_name, $price, $filename, $product_id]);

  // Redirect back to product list
  header('Location: productslist.php');
  exit();
}
?>

<!-- Your HTML code goes here -->
