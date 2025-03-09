<?php 

include('../db_connection/conn.php');

if (isset($_POST['add_user'])) {
    $username = $_POST['user_name'];
    $email = $_POST['user_email'];
    $password = password_hash($_POST['user_pwd'], PASSWORD_DEFAULT);
    $phone_number =  $_POST['user_phone'];
    $address =  $_POST['user_address'];
    $role =  $_POST['user_role'];
    // $user_image = null;

    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

    // Check if user already exists
    $check_user = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
    $check_user->execute([$email]);
    if ($check_user->rowCount() > 0) {
        $message = 'User already exists with this email!';
    } else {
        $query = "INSERT INTO `user`( `name`, `password`, `email`, `phone_number`, `address`, `role`) VALUES (:name, :password, :email, :phone_number,:address,:role)";
        $statment = $conn->prepare($query);
        $data = [
            'name' => $username,
            'password' => $password,
            'email' =>$email ,
            'phone_number' => $phone_number,
            'address' => $address,
            'role' => $role,
            // 'profile_image' => $user_image // Corrected here
        ];
        $statment->execute($data);
        header('Location: ../index.php');
    } 
    }


    //image uploader
    // if (!empty($_FILES['user_image']['name'])) {
    //     $img_name = $_FILES['user_image']['name'];
    //     $img_tmp = $_FILES['user_image']['tmp_name'];
    //     $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    //     $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

    //     if (in_array($img_ext, $allowed_exts)) {
    //         $new_img_name = 'user' . time() . ".$img_ext";
    //         if (move_uploaded_file($img_tmp, "../images/$new_img_name")) {
    //             $user_image = $new_img_name; // Set the image path
    //         } else {
    //             $message[] = 'Sorry, there was an error uploading your file.';
    //         }
    //     } else {
    //         $message[] = 'Invalid file type! Only JPG, PNG, GIF allowed.';
    //     }
    // }

    
?>