
<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $product_name = $_POST["product_name"];
    $price = $_POST["price"];
    $image = $_POST["image"];

    // Insert data into the table
    $sql = "INSERT INTO products (product_name, price, image) VALUES ('$product_name', '$price', '$image')";
    if (mysqli_query($conn, $sql)) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}
?>