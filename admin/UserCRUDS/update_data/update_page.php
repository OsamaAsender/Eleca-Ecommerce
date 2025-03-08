<?php
include('../db_connection/conn.php');

// read from student table based on id
// echo "update page";


?>



<?php
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $query = "SELECT * FROM `user` WHERE `id` = :id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $user_inf = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user_inf) {
            // User data fetched successfully
            // echo '<pre>';
            // print_r($user_inf);
            // echo '</pre>';
        } else {
            echo "No user found with the provided ID.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "ID not set.";
    exit();
}
?>


<!-- update on student table based on id -->
<?php
if(isset($_POST['update_user'])){
    // echo "update data";
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $phone_number = filter_input(INPUT_POST, 'user_phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'user_address', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'user_role', FILTER_SANITIZE_STRING);
    






    // Prepare the update query
//     $query = "UPDATE `user` SET `name`=:name, `email`=:email, `phone_number`=:phoneNumber, `password`=:password, `address`=:address, `role`=:role WHERE `id`=:id";


//     $statment = $conn->prepare($query);
//     $statment->bindParam(':name', $username);
//     $statment->bindParam(':email', $email);
//     $statment->bindParam(':phoneNumber', $phone_number);
//     $statment->bindParam(':password', $password);
//     $statment->bindParam(':address', $address);
//     $statment->bindParam(':role', $role);

//     // Execute the statement
//     $statment->execute();
//     header('location:../index.php?message=update successful');


$query = "UPDATE `user` SET `name`=:name, `email`=:email, `phone_number`=:phoneNumber, `password`=:password, `address`=:address, `role`=:role WHERE `id`=:id";

$statment = $conn->prepare($query);
$statment->bindParam(':name', $username);
$statment->bindParam(':email', $email);
$statment->bindParam(':phoneNumber', $phone_number);
$statment->bindParam(':password', $password);
$statment->bindParam(':address', $address);
$statment->bindParam(':role', $role);
$statment->bindParam(':id', $id); // Bind the ID here

// Execute the statement
try {
    $statment->execute();
    header('location:../index.php?message=update successful');


} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // Error handling
}
}




?>

<!-- 
<form action="" method="post">
    <input type="hidden" name="category_id" value="<?= $user_inf['id']?>">
    <div class="mb-3">
        <label for="user_name" class="col-form-label">Name:</label>
        <input type="text" class="form-control" id="user_name" name="user_name" value="<?= $user_inf['name']?>">
    </div>
    <div class="mb-3">
        <label for="user_email" class="col-form-label">Email:</label>
        <input type="text" class="form-control" id="user_email" name="user_email" value="<?= $user_inf['email']?>">
    </div>
    <div class="mb-3">
        <label for="user_pwd" class="col-form-label">Password:</label>
        <input type="text" class="form-control" id="user_pwd" name="user_pwd" value="<?= $user_inf['password']?>">
    </div>
    <div class="mb-3">
        <label for="user_phone" class="col-form-label">Phonenumber:</label>
        <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?= $user_inf['phone_number']?>">
    </div>
    <div class="mb-3">
        <label for="user_name" class="col-form-label">Address:</label>
        <input type="text" class="form-control" id="user_address" name="user_address" value="<?= $user_inf['address']?>">
    </div>
    <div class="mb-3">
    <select name="user_role" id="user_role" value="<?= $user_inf['role']?>">
                                            <option value="user">user</option>
                                            <option value="admin">admin</option>
                                            <option value="super admin">super admin</option>
                                        </select>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-success" name="update_user" value="update">
    </div>
</form> -->

