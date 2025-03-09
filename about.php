<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p>Choose us for a seamless shopping experience with top-quality electronics at competitive prices.
             Our e-commerce platform offers a wide range of the latest gadgets and accessories, ensuring you get the best products with fast delivery, secure payments, and exceptional customer service.
         </p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

   </div>

</section>

<section class="reviews">
   <h1 class="heading">Client's Reviews</h1>

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <img src="images/pic-3.png" alt="Client John Deo">
            <p>"The shopping experience was fantastic! I ordered a new laptop, and it arrived quickly, well-packaged, and in perfect condition. The site was easy to navigate, and I’m really happy with my purchase!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
            </div>
            <h3>John Deo</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-4.png" alt="Client Jane Doe">
            <p>"I bought a smartphone from this site, and the whole process was smooth. I got great value for money, and their customer support was very responsive when I had questions. Highly recommend!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
               <i class="fas fa-star"></i>
            </div>
            <h3>Jane Doe</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-5.png" alt="Client Alex Smith">
            <p>"I’ve been shopping for electronics online for years, and this site is one of the best. The selection is amazing, and the checkout process was simple. The delivery was on time, and my tablet is perfect!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
            </div>
            <h3>Alex Smith</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-6.png" alt="Client Sophia Lee">
            <p>"Absolutely i will buy love my new headphones! The sound quality is incredible, and they arrived much faster than expected. I’ll definitely be coming back to shop here for more gadgets!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
               <i class="fas fa-star"></i>
            </div>
            <h3>Sophia Lee</h3>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>