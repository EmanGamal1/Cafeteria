<?php

// Connect to database
include '../dbconfig.php';

$db =connect_pdo() ;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// Get form data
$product_id = $_GET['id'];
$product_name = $_POST['product_Name'];
$price = $_POST['price'];
$image = $_POST['image'];

// Get the new image file from the form
$image = $_FILES['image'];

// Check if an image file has been uploaded
if (!empty($image['name'])) {
  // Get the file extension
  $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

  // Generate a unique filename
  $filename = uniqid() . ".$ext";

  // Set the path where the file will be uploaded
  $path = '../images/products' . $filename;

  // Upload the file to the server
  move_uploaded_file($image['tmp_name'], $path);

// Update product in database
$stmt = $db->prepare("UPDATE products SET product_Name = ?, price = ?, image = ? WHERE product_id = ?");
$stmt->execute([$product_name, $price, $filename, $product_id]);
  
// Redirect back to product list
header('Location: productslist.php');
exit();
  }}
?>


