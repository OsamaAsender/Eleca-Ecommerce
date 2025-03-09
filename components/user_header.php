<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <!-- Font awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="./css/style.css">
   <link rel="stylesheet" href="./css/header_style.css">

   <style>
       /* Modal styles */
       .modal {
           display: none; /* Hidden by default */
           position: fixed;
           z-index: 2; /* Sit on top */
           left: 0;
           top: 0;
           width: 100%; /* Full width */
           height: 100%; /* Full height */
           background-color: rgba(0, 0, 0, 0.5); /* Black background with transparency */
           justify-content: center;
           align-items: center;
       }

       .modal-content {
           background-color: #fff;
           padding: 20px;
           border-radius: 10px;
           width: 300px;
           text-align: center;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
       }

       .modal-button {
           padding: 10px 20px;
           margin: 5px;
           cursor: pointer;
           border: none;
           border-radius: 5px;
           font-size: 16px;
       }

       .modal-button:hover {
           background-color: #ddd;
       }

       .cancel-button {
           background-color: #f44336; /* Red */
           color: white;
       }

       .confirm-button {
           background-color: #4CAF50; /* Green */
           color: white;
       }
   </style>
</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>
<header class="header">
<section class="flex">

   <a href="home.php" class="logo">Eleca</a>

   <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="about.php">About</a>
      <a href="shop.php">Products</a>
      <a href="orders.php">Orders</a>
      <a href="contact.php">Contact</a>
   </nav>

   <div class="icons d-flex align-items-center gap-2">
    <?php
        $count_wishlist_item = $conn->prepare("SELECT COUNT(*) FROM `wishlist` WHERE id = ?");
        $count_wishlist_item->execute([$user_id]);
        $total_wishlist_counts = $count_wishlist_item->rowCount();

        $count_order_items = $conn->prepare("SELECT COUNT(*) FROM `order` WHERE id = ?");
        $count_order_items->execute([$user_id]);
        $total_order_counts = $count_order_items->rowCount();
    ?>
    <div id="menu-btn" class="fas fa-bars fs-5"></div>
    <a href="wishlist.php" class="text-dark fs-6 d-flex align-items-center">
        <i class="fas fa-heart"></i>
        <span class="ms-1">(<?= $total_wishlist_counts; ?>)</span>
    </a>
    <a href="orders.php" class="text-dark fs-6 d-flex align-items-center">
        <i class="fas fa-shopping-cart"></i>
        <span class="ms-1">(<?= $total_order_counts; ?>)</span>
    </a>
    <div id="user-btn" class="fas fa-user fs-5"></div>
</div>

   <div class="profile">
      <?php          
         $select_profile = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
         $select_profile->execute( [$user_id]);
         if($select_profile->rowCount() > 0){
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <p><?= $fetch_profile["name"]; ?></p>
      <a href="update_user.php" class="btn">update profile</a>
      <div class="flex-btn">
         <a href="user_register.php" class="option-btn">register</a>
         <a href="user_login.php" class="option-btn">login</a>
      </div>
      <a href="javascript:void(0);" id="logoutLink" class="delete-btn">Logout</a>
      <?php
         } else {
      ?>
      <p>please login or register first!</p>
      <div class="flex-btn">
         <a href="user_register.php" class="option-btn">register</a>
         <a href="user_login.php" class="option-btn">login</a>
      </div>
      <?php
         }
      ?>      
   </div>

</section>
</header>

<!-- Logout Confirmation Modal -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h3>Are you sure you want to log out?</h3>
        <button class="modal-button confirm-button" id="confirmLogout">Yes</button>
        <button class="modal-button cancel-button" id="cancelLogout">No</button>
    </div>
</div>

<script>
// Get elements for modal and logout link
const logoutLink = document.getElementById('logoutLink');
const confirmModal = document.getElementById('confirmModal');
const confirmLogout = document.getElementById('confirmLogout');
const cancelLogout = document.getElementById('cancelLogout');

// Event listener for the logout link
logoutLink.onclick = function(e) {
    // Prevent the default action of the link (logout)
    e.preventDefault();

    // Show the confirmation modal
    confirmModal.style.display = 'flex'; // Using flex to center the modal

    // If user clicks "Yes", proceed with logout
    confirmLogout.onclick = function() {
        window.location.href = 'components/user_logout.php?logout=yes'; // Redirect to logout URL
    };

    // If user clicks "No", hide the modal
    cancelLogout.onclick = function() {
        confirmModal.style.display = 'none'; // Close the modal
    };
};
</script>

</body>
</html>
