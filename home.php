<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};



?>


   
<?php include './components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-1.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>latest smartphones</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/aa.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>latest Tvs</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/laptop.png" alt="">
         </div>
         <div class="content">
            <span>up to 50% off</span>
            <h3>latest laptops</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">shop by category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="shop.php" class="swiper-slide slide">
      <img src="images/icon-1.png" alt="">
      <h3>laptop</h3>
   </a>

   <a href="shop.php" class="swiper-slide slide">
      <img src="images/icon-2.png" alt="">
      <h3>tv</h3>
   </a>

   <a href="shop.php" class="swiper-slide slide">
      <img src="images/icon-7.png" alt="">
      <h3>smartphone</h3>
   </a>

   

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>
 

<style>
   .box-container {
      display: grid;
         grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
         gap: 50px;
         justify-content: center;
         margin-top: 20px;
   }

   .product-box {
      background-color: #fff;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease;
         text-align: center;
   }

   .product-box:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
   }

   .product-box img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 10px;
      transition: transform 0.3s ease;
   }

   .product-box .name {
      font-size: 1.4em;
      margin-top: 15px;
      color: #333;
   }

   .product-box p {
      font-size: 2em;
      color: #2980b9;
      margin-bottom: 10px;
      margin-top: 20px;

   }

   .wishlist-btn, .add-to-order-btn {
      width: 100%;
      padding: 12px;
      font-size: 1.1em;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s;
      margin-top: 10px;
      margin-bottom: 5px;
      font-weight: bold;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   }
   

   .wishlist-btn:hover, .add-to-order-btn:hover {
      background-color: #0056b3;
      transform: scale(1.05);
   }
   .product-box .name {
   font-size: 1.4em;
   margin-top: 15px;
   color: #333;
   font-weight: bold;
   text-transform: uppercase;
   text-align: center;
   background-color: #2980b9;
   color: white;
   padding: 10px;
   border-radius: 40px;
}
.product-link {
   text-decoration: none;
   color: inherit;
}

.product-box {
   transition: transform 0.3s ease;
}

.product-box:hover {
   transform: scale(1.05);
}
.view-product-btn {
   display: inline-block;
   padding: 10px 15px;
   background-color: #2980b9;
   color: white;
   text-decoration: none;
   border-radius: 5px;
   margin-top: 10px;
   font-weight: bold;
   transition: background-color 0.3s, transform 0.3s;
}

.view-product-btn:hover {
   background-color: #0056b3;
   transform: scale(1.05);
}



</style>



<section class="products container">
   <h2 class="heading">Latest Products</h2>
   <div class="box-container">
   
   <?php
     $select_product = $conn->prepare("SELECT * FROM `product` LIMIT 3"); 
     $select_product->execute();
     if($select_product->rowCount() > 0){
      while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="product-box" style="width: 330px;heigth: 400px;">
      
         <img src="images/<?= htmlspecialchars($fetch_product['image']) ?: 'default_product.png'; ?>" style="width:300px;height:200px" alt="Product Image">
         <div class="name"> <?= htmlspecialchars($fetch_product['name']); ?> </div>
         <p>Price: JD <?= htmlspecialchars($fetch_product['price']); ?></p>
      
      <a href="shop.php?id=<?= $fetch_product['id']; ?>" class="view-product-btn">
         View Product
      </a>
   </div>

   <?php
      }
   }else{
      echo '<p class="empty">No products added yet!</p>';
   }
   ?>
   
   </div>
</section>










<?php include 'components/footer.php'; ?>




<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});



</script>



<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>