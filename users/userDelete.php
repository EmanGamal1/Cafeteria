<?php
require '../dbconfig.php';
$db=connect_pdo();
$user_id = $_GET['id'];

// Retrieve the file name for the user from the database
$query = "SELECT image FROM `php-eman`.`users` WHERE id=:user_id";
$select_stmt = $db->prepare($query);
$select_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$select_stmt->execute();
$user_data = $select_stmt->fetch(PDO::FETCH_ASSOC);
$file = $user_data['image'];
var_dump($file);

// Delete the user from the database
$query = "DELETE FROM `php-eman`.`users` WHERE id=:user_id";
$delete_stmt = $db->prepare($query);
$delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$res = $delete_stmt->execute();

if ($delete_stmt->rowCount() > 0) {
    // Delete the user data from the database
    $query = "DELETE FROM `php-eman`.`users` WHERE id=:user_id";
    $delete_stmt = $db->prepare($query);
    $delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $delete_stmt->execute();

    // Delete the user's image file from the folder
       if (file_exists($file)) {
        unlink($file);
    }
}
header('Location:usersList.php');
?>
