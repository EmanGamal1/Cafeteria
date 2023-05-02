<?php
require '../dbconfig.php';
$db=connect_pdo();
var_dump($_POST);

    $Name=$_POST['Name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $confirmed=$_POST['confirm-password'];
    $room=$_POST['room'];
    $ext=$_POST['ext'];
    $confirm=$_POST['confirm-password'];
    $date = date_create();
    $id= date_timestamp_get($date);
    $pattern="/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
    $passPattern='/^[a-z_]+[a-z_]$/';

    ##the way it is saved in a file    
    
## validate data
    $errors = [];
    $formvalues = [];
    if(!isset($Name) or empty($Name)){
    $errors["Name"]='Name is required';
    }else{
    $formvalues["Name"]= $Name;
    }
    if(!isset($email) or empty($email) or !preg_match($pattern,$email)){
    $errors["email"]='Email is required';
    }else{
    $formvalues["email"]= $email;
    }
    if(!isset($password) or empty($password) or (!preg_match($passPattern,$password) and mb_strlen($password) != 8)){
    $errors["password"]='Password is required';
    }else{
    $formvalues["password"]= $password;
    }
    if(!isset($room) or empty($password)){
        $errors["room"]='Room is required';
    }else{
        $formvalues["password"]= $password;
    }
    if(!isset($ext) or empty($ext)){
        $errors["ext"]='Ext is required';
    }else{
        $formvalues["password"]= $password;
    }
    if($confirmed != $password){
        $errors["confirmed"]='Password required';
    }else{
        $formvalues["confirmed"]= $confirmed;
    }
    $formerrors=json_encode($errors);

    if($errors){
    $redirect_url = "Location:userAdd.php?errors={$formerrors}";
    if ($formvalues){
    $oldvalues = json_encode($formvalues);
    $redirect_url .="&old={$oldvalues}" ;
    }

    header($redirect_url);

    }
    
    if(!$errors) {
        $file_name = $_FILES['file']['name'];
        $file_size =$_FILES['file']['size'];
        $file_tmp =$_FILES['file']['tmp_name'];
        $file_type=$_FILES['file']['type'];
        ## validation on the file? ----> extension ? size ? moving to known place
        $extension = explode('.',basename($file_name));
        var_dump(end($extension));
        $allowed_extenstions=["png", 'jpg', 'jpeg'];
        var_dump(sys_get_temp_dir());
        
        if(in_array(end($extension), $allowed_extenstions)){
            echo "Valid image ";
            $res=move_uploaded_file($file_tmp,"../images/users/{$file_name}");
            $imagespath = "../images/users/{$file_name}";
        }
        
        $query="INSERT INTO `php-eman`.`users` (`name`,`email`,`password`,`room`,`image`,`ext`)
                       Values(?,?,?,?,?,?)";
                       
           $stmt = $db->prepare($query);
           $res=$stmt->execute([$Name, $email, $password, $room, $imagespath, $ext]);


        $redirect_url = "Location:usersList.php";
        header($redirect_url);
 
}

?>