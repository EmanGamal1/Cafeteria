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
<body>
    <div class="container-fluid">
 <?php
 require_once '../header.html';
 ?>
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
        <?php
        require_once '../footer.html';
        ?>
    </div>

</body>

</html>