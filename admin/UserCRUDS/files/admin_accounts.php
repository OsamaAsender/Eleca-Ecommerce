<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit();
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   // Delete from the user table where the role is admin
   $delete_admin = $conn->prepare("DELETE FROM `user` WHERE id = ? AND role = 'admin'");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
   exit();
}

include '../components/admin_header.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<section class="accounts">

   <h1 class="heading">Admin Accounts</h1>

   <div class="box-container">

   <div class="box">
      <p>Add new admin</p>
      <a href="register_admin.php" class="option-btn">Register Admin</a>
   </div>

   <?php
      // Select users where the role is 'admin'
      $select_accounts = $conn->prepare("SELECT * FROM `user` WHERE role = 'admin'");
      $select_accounts->execute();
      if ($select_accounts->rowCount() > 0) {
         while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <div class="box">
      <p> Admin ID : <span><?= htmlspecialchars($fetch_accounts['id']); ?></span> </p>
      <p> Admin Name : <span><?= htmlspecialchars($fetch_accounts['name']); ?></span> </p>
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account?')" class="delete-btn">Delete</a>
         <?php
            if ($fetch_accounts['id'] == $admin_id) {
               echo '<a href="update_profile.php" class="option-btn">Update</a>';
            }
         ?>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No admin accounts available!</p>';
      }
   ?>

   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
