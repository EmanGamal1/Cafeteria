<?php
require '../dbconfig.php';
$db=connect_pdo();

$errors = [];
    $user=[];
    if($_GET && isset($_GET['errors'])){
        $errors = json_decode($_GET['errors']);
        $errors = (array) $errors;
    }else{
        $user_id = $_GET['id'];
        $query = "SELECT id,name, email, room, ext, image FROM users where id = $user_id";
        $stmt=$db->prepare($query);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_NUM);
        var_dump($result);
        foreach ($result as $index=>$user){
        //   var_dump($user);
            $id=$user[0];
            $Name=$user[1];
            $email=$user[2];
            $room=$user[3];
            $ext=$user[4];
            $image=$user[5];
         }
    }
?>

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
<?php
require_once '../header.html';
?>

    <div class="container mt-4">
        <form action="userUpdateValidation.php" method=" post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $user_id ?>">

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="text" class="form-control form-control-sm" name="Name" value="<?php echo $Name?>">
                <div class="text-danger"> <?php  if(isset($errors['Name']))  echo $errors['Name']; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control form-control-sm" aria-describedby="emailHelp" name="email"
                    value="<?php echo $email?>">
                <div class="text-danger"> <?php  if(isset($errors['email']))  echo $errors['email']; ?></div>


            </div>
            <div class="mb-3">
                <label class="form-label">Room No</label>
                <input type="number" class="form-control" id="room" name="room" min="2000" max="4000" step="1"
                    value="<?php echo $room?>">

            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Ext</label>
                <input type="number" class="form-control form-control-sm" id="exampleInputPassword1" name="ext"
                    value="<?php echo $ext?>">
            </div>
            <div class="form-group">
                <label for="image">image</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Image</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="file">
                        <label class="custom-file-label" for="image">
                            <?php echo $image?>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-danger">Update</button>
        </form>
    </div>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>