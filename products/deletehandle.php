<?php
require_once  '../dbconfig.php';
   $db = connect_pdo();

     $product_id =  $_GET['id'];
    $query="Delete from `products` where product_id=:product_id ";


    $delete_stmt = $db->prepare($query);

    $delete_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $res=$delete_stmt->execute();
    var_dump($res);
    var_dump($delete_stmt->rowCount());

    header('Location:productsList.php');