<?php
$host = "nader-mo.tech";
$username = "php-eman";
$password = "Aa123456";
$database = "php-eman";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>