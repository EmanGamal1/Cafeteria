<?php
require_once './home.php';

$pdo = connect_pdo();

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM products WHERE product_Name LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM products";
}


$stmt = $pdo->query($sql);
if ($stmt->rowCount() > 0) {
    echo '<h4 class="text-center" style="display: flex; align-items: center;"><hr style="flex: 1;"><span class="mx-5"> Products </span><hr style="flex: 1;"></h4><br>';
    echo '<div class="row">';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="col-md-3 mb-3">';
        echo '<div class="text-center">';
        echo '<img width="150px" src="../images/products/' . $row["image"] . '" class="product-img" data-product-id="' . $row["product_id"] . '">';
        $product_Name = "product_Name";
        $product_id = "product_id";
        echo '<br>' . '<p class="product-name">' . $row["{$product_Name}"] . '</p>';
        echo '<p>' . '<span class="price">' . $row["price"] . '</span>' . '<span> EGP</span>' . '</p>';
        echo '</div></div>';
        echo '<div id="added-product-name"></div>';
    }
    echo '</div>';
} else {
    echo "0 results";
}
?>
