<?php
include('../db_connection/conn.php');

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $query = "SELECT * FROM `user` WHERE `id` = :id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $user_inf = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$user_inf) {
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

if(isset($_POST['update_user'])){
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $phone_number = filter_input(INPUT_POST, 'user_phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'user_address', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'user_role', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'user_pwd', FILTER_SANITIZE_STRING);

    try {
        $query = "UPDATE `user` SET 
            `name` = :name,
            `email` = :email,
            `phone_number` = :phoneNumber,
            `password` = :password,
            `address` = :address,
            `role` = :role 
            WHERE `id` = :id";

        $statement = $conn->prepare($query);
        $statement->bindParam(':name', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':phoneNumber', $phone_number);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':address', $address);
        $statement->bindParam(':role', $role);
        $statement->bindParam(':id', $id);

        if ($statement->execute()) {
            // Redirect to the same page with the id and a success message
            $message = urlencode('update successful');
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<form action="" method="post">
    <div class="mb-3">
        <label for="user_name" class="col-form-label">Name:</label>
        <input type="text" class="form-control" id="user_name" name="user_name" value="<?= htmlspecialchars($user_inf['name']) ?>">
    </div>
    <div class="mb-3">
        <label for="user_email" class="col-form-label">Email:</label>
        <input type="email" class="form-control" id="user_email" name="user_email" value="<?= htmlspecialchars($user_inf['email']) ?>">
    </div>
    <div class="mb-3">
        <label for="user_pwd" class="col-form-label">Password:</label>
        <input type="text" class="form-control" id="user_pwd" name="user_pwd" value="<?= htmlspecialchars($user_inf['password']) ?>">
    </div>
    <div class="mb-3">
        <label for="user_phone" class="col-form-label">Phonenumber:</label>
        <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?= htmlspecialchars($user_inf['phone_number']) ?>">
    </div>
    <div class="mb-3">
        <label for="user_address" class="col-form-label">Address:</label>
        <input type="text" class="form-control" id="user_address" name="user_address" value="<?= htmlspecialchars($user_inf['address']) ?>">
    </div>
    <div class="mb-3">
        <label for="user_role" class="col-form-label">Role:</label>
        <select name="user_role" id="user_role" class="form-select">
            <option value="user" <?= $user_inf['role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user_inf['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="super admin" <?= $user_inf['role'] === 'super admin' ? 'selected' : '' ?>>Super Admin</option>
        </select>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-success" name="update_user" value="Update">
    </div>
</form>