<?php
  require '../dbconfig.php';
  $db=connect_pdo();
    // Define how many results you want per page
    $results_per_page = 5;
    
    // Find out the number of results stored in database
    $query = "SELECT * FROM users";
    $stmt=$db->prepare($query);
    $stmt->execute();
    $number_of_results = $stmt->rowCount();
    
    // Determine number of total pages available
    $number_of_pages = ceil($number_of_results/$results_per_page);

    // Determine which page number visitor is currently on // Get value of page parameter from URL if it exists, else it will equal page 1
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    // Determine the sql LIMIT starting number for the results on the displaying page // e.g. page 1 will show results 1-5, page 2 will show results 6-10
    $this_page_first_result = ($page-1)*$results_per_page;

    // Retrieve selected results from database and display them on page
    $query = "SELECT * FROM users LIMIT " . $this_page_first_result . ',' .  $results_per_page;

    $stmt=$db->prepare($query);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_NUM);
    // var_dump($result);

  ?>

<!DOCTYPE html>
<html>

<head>
    <title>Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="../home/homePage.php">Cafeteria</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="../home/homePage.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../products/productsList.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../orders/manualOrder.php">Manual Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../checks/checksList.php">Checks</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <div class="head d-flex justify-content-between align-items-center">
                <h1>All Users</h1>
                <button type="button" class="btn btn-success " onclick="location.href='userAdd.php'">Add User</button>
            </div>
        </div>
        <div>
            <?php

    echo "<div class='container mt-5'> ";
        $query = "SELECT id,name, email, room, ext, image FROM users";
        $stmt=$db->prepare($query);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_NUM);
        // var_dump($result);

        echo "<table class='table table-striped text-center text-danger'>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Room</th>
                <th>Ext</th>
                <th>Image</th>
                <th></th>
                <th></th>
            </tr>";
            foreach ($result as $data){
                echo "<tr>";
                echo "<td>". $data[0] . "</td>";
                echo "<td>". $data[1] . "</td>";
                echo "<td>". $data[2] . "</td>";
                echo "<td>". $data[3] . "</td>";
                echo "<td>". $data[4] . "</td>";
                echo "<td><img src='". $data[5] . "' width='50' height='50'></td>";
                echo "<td> <a href='userDelete.php?id={$data[0]}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a> </td>";
                echo "<td> <a href='userUpdate.php?id={$data[0]}' class='btn btn-success'>Edit</a> </td>";


                echo "</tr>";
            }
            echo "</table>";
            echo "<div class='d-flex justify-content-center'>";
            echo "<ul class='pagination'>";
            // Display the links to the pages
            for ($page=1;$page<=$number_of_pages;$page++) {
                $class = '';
                if (isset($_GET['page']) ){
                    if ($page == $_GET['page'])
                    $class = ' active';
                }
            echo "<li class='page-item$class'><a class='page-link' href='usersList.php?page=$page'>$page</a></li>";
            
        }
        echo "</ul>";
    ?>
        </div>
        <footer class="bg-light text-center mt-4">
            <p>&copy; Cafeteria. All rights reserved.</p>
        </footer>
    </div>

</body>

</html>