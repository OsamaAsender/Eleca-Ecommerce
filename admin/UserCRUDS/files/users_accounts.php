<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

// if (!$admin_id) {
//     header('location:admin_login.php');
//     exit();
// }

// Handle Add User
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    // Check if user already exists
    $check_user = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
    $check_user->execute([$email]);
    if ($check_user->rowCount() > 0) {
        $message = 'User already exists with this email!';
    } else {
        $insert_user = $conn->prepare("INSERT INTO `user` (name, email, password, role) VALUES (?, ?, ?, ?)");
        $insert_user->execute([$username, $email, $password, $role]);
        $message = 'New user added successfully!';
    }
}

// Handle Update User
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $update_user = $conn->prepare("UPDATE `user` SET name = ?, email = ?, password = ? WHERE id = ?");
        $update_user->execute([$username, $email, $password, $user_id]);
    } else {
        $update_user = $conn->prepare("UPDATE `user` SET name = ?, email = ? WHERE id = ?");
        $update_user->execute([$username, $email, $user_id]);
    }
    $message = 'User details updated successfully!';
}

// Handle Delete User
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_user = $conn->prepare("DELETE FROM `user` WHERE id = ?");
    $delete_user->execute([$delete_id]);

    $delete_orders = $conn->prepare("DELETE FROM `order` WHERE user_id = ?");
    $delete_orders->execute([$delete_id]);

    $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
    $delete_messages->execute([$delete_id]);

    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$delete_id]);

    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
    $delete_wishlist->execute([$delete_id]);

    header('location:users_accounts.php');
}

include '../components/admin_header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Accounts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            margin: 10% auto;
            width: 40%;
            border-radius: 10px;
            position: relative;
        }

        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: black;
        }
    </style>
</head>
<body>

<section class="accounts">
    <h1 class="heading">User Accounts</h1>

    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

    <div class="box-container">
        <?php
        $select_accounts = $conn->prepare("SELECT * FROM `user` WHERE role = 'user'");
        $select_accounts->execute();
        if ($select_accounts->rowCount() > 0) {
            while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>User ID: <span><?= $fetch_accounts['id']; ?></span></p>
            <p>Username: <span><?= $fetch_accounts['name']; ?></span></p>
            <p>Email: <span><?= $fetch_accounts['email']; ?></span></p>
            <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account? The user-related information will also be deleted!')" class="delete-btn">Delete</a>
            <button class="btn edit-btn" 
                onclick="editUser('<?= $fetch_accounts['id']; ?>', 
                                  '<?= htmlspecialchars($fetch_accounts['name']); ?>', 
                                  '<?= htmlspecialchars($fetch_accounts['email']); ?>')">Edit</button>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No user accounts available!</p>';
        }
        ?>
    </div>

    <!-- Add User Form -->
    <div class="add-user-form">
        <h2>Add New User</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter username" required>
            <input type="email" name="email" placeholder="Enter email" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <input type="submit" name="add_user" value="Add User" class="btn">
        </form>
    </div>
</section>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Edit User</h2>
        <form action="" method="POST">
            <input type="hidden" name="user_id" id="user_id">
            <input type="text" name="username" id="username" placeholder="Enter username" required>
            <input type="email" name="email" id="email" placeholder="Enter email" required>
            <input type="password" name="password" placeholder="Enter new password (leave blank to keep current)">
            <input type="submit" name="update_user" value="Update User" class="btn">
        </form>
    </div>
</div>

<script>
// Get modal and close button
var modal = document.getElementById("editUserModal");
var closeBtn = document.getElementsByClassName("close-btn")[0];

// Function to open modal and populate fields
function editUser(userId, username, email) {
    document.getElementById("user_id").value = userId;
    document.getElementById("username").value = username;
    document.getElementById("email").value = email;
    modal.style.display = "block";
}

// Close modal on clicking X button
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<script src="../js/admin_script.js"></script>

</body>
</html>
