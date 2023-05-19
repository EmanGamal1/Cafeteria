<?php
require '../dbconfig.php';
$db=connect_pdo();

var_dump($_FILES);
    $id=$_POST['id'];
    $Name=$_POST['Name'];
    $email=$_POST['email'];
    $room=$_POST['room'];
    $ext=$_POST['ext'];  
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
    
    if(!isset($room) or empty($room)){
        $errors["room"]='Room is required';
    }else{
        $formvalues["roomm"]= $room;
    }
    if(!isset($ext) or empty($ext)){
        $errors["ext"]='Ext is required';
    }else{
        $formvalues["ext"]= $ext;
    }
   
    $formerrors=json_encode($errors);

    if($errors){
    $redirect_url = "Location:userUpdate.php?errors={$formerrors}";
    if ($formvalues){
    $oldvalues = json_encode($formvalues);
    $redirect_url .="&old={$oldvalues}" ;
    }

    // header($redirect_url);

    }
    
    if(isset($_FILES['file'])){
        $file_name = $_FILES['file']['name'];
        $file_size =$_FILES['file']['size'];
        $file_tmp =$_FILES['file']['tmp_name'];
        $file_type=$_FILES['file']['type'];
        var_dump($file_tmp);
        ## validation on the file? ----> extension ? size ? moving to known place
        $extension = explode('.',basename($file_name));
        var_dump(end($extension));
        $allowed_extenstions=["png", 'jpg', 'jpeg'];
        var_dump(sys_get_temp_dir());
        
        if(in_array(end($extension), $allowed_extenstions)){
            echo "Valid image ";
            // $res=move_uploaded_file($file_tmp,"../images/users/{$file_name}");
            // $imagespath = "../images/users/{$file_name}";
            //update image 
            $upload_dir = "../images/users/";
            $new_file_path = $upload_dir . basename($file_name);
            if(move_uploaded_file($file_tmp, $new_file_path)) {
            // file uploaded successfully, update the image field in the database
                $query="update users set name=?, email=?, room=?,image=?, ext=? where id=?";
                    
                $stmt = $db->prepare($query);
                $res=$stmt->execute([$Name, $email, $room, $new_file_path, $ext, $id]);

            }else{
                $errors['file'] = "Error uploading file";
            }
        } else {
            // file is not a valid image, handle the error
            $errors['file'] = "Invalid file type";
        }
    } else {
        // no new image has been uploaded, continue with updating other fields
        $query = "UPDATE users SET name=?, email=?, room=?, ext=? WHERE id=?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$Name, $email, $room, $ext, $id]);
    }
    
        $redirect_url = "Location:usersList.php";
        header($redirect_url);
 

?>