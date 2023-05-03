<!DOCTYPE html>
<html>
<body>
<?php
require_once '../header.html';
?>

<div class="container mt-4">
  <h1>My Orders</h1>
</div>

<?php
require_once 'orderListRetrieve.php';
require_once '../footer.html';
?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<?php
$pdo = null;
?>
</body>
</html>