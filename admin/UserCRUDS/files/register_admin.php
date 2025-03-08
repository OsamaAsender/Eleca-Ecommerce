<?php
include '../components/connect.php';
// include '../components/user_header.php';
// user_header.php
session_start();

if(isset($_POST['submit'])){

   $name = trim($_POST['name']);
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = trim($_POST['email']);
   $email = filter_var($email, FILTER_VALIDATE_EMAIL);
   $phone = trim($_POST['phone']);
   $address = trim($_POST['address']);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   if (!$email) {
      $message[] = 'Invalid email format!';
   } else {
      // Update to match your `user` table column name: `email`
      $select_user = $conn->prepare("SELECT * FROM `user` WHERE `email` = ?");
      $select_user->execute([$email]);

      if ($select_user->rowCount() > 0) {
         $message[] = 'Email already exists!';
      } else {
         if ($pass !== $cpass) {
            $message[] = 'Confirm password does not match!';
         } else {
            $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
            // Update to match your `user` table column names: `name`, `email`, `password`, `phone_number`, `address`, `role`
            $insert_user = $conn->prepare("INSERT INTO `user` (`name`, `email`, `password`, `phone_number`, `address`, `role`) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_user->execute([$name, $email, $hashed_pass, $phone, $address, 'admin']);
            $message[] = 'New admin registered successfully!';
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<section class="form-container">
   <form action="" method="post">
      <h3>Register Admin</h3>
      <?php
      if (isset($message)) {
         foreach ($message as $msg) {
            echo "<p class='message'>$msg</p>";
         }
      }
      ?>
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box">
      <input type="text" name="phone" required placeholder="Enter your phone number" maxlength="20" class="box">
      <textarea name="address" required placeholder="Enter your address" class="box"></textarea>
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register Now" class="btn" name="submit">
   </form>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
