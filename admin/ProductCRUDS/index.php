<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // echo $user_id;   
} else {
    header('location:user_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <title>admin dashboard</title>
</head>

<body>
    <div class="d-flex">
        <div id="sidebar">
            <button class="btn text-white sidebarHeaderbutton">Dashboard</button>
            <a href="../UserCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\man.png" alt="" class="me-1"> Users</button></a>
            <a href="../CategoryCRUDS\index.php"> <button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\categories.png" alt="" class="me-1"> Categories</button></a>
            <a href="#">
                <button class="btn CustomSidebarButtons text-white"><img src="../flaticon\product.png" alt=""
                        class="me-1">
                    Products</button>
            </a>


            <a href="../CouponCRUDS"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\coupon.png" alt="" class="me-1"> Coupons</button></a>
            <a href="../OrderRU/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\received.png" alt="" class="me-1"> Orders</button></a>


        </div>
        <div class="page-content">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="">
                        <button class="btn fw-bold">ELECA SHOP</button>
                    </div>


                    <div class="me-5 pe-4">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="../flaticon/profile.png" alt="asdfsadf">
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="../components/profile.php"><i
                                                class="fa-solid fa-user"></i> Profile</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="../components/logout.php?logout=yes" class="logout-btn btn">Log out</a></li>
                                </ul>
                            </li>
                        </ul>

                    </div>

                </div>

            </nav>

            <?php

            include('db_connection/conn.php');

            if (isset($_GET['message'])) {
                echo "<div style='background-color:green'> {$_GET['message']}</div>";
            }
            ?>

            <main>



                <div class="container my-5">
                    <h1>Product Information</h1>
                    <!-- FIX: Updated data attributes for Bootstrap 5 -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        Add Product
                    </button>

                </div>

                <div class="container mt-5">
                    <table class="table table-striped table-hover table">
                        <thead>
                            <tr>
                                <!-- <th scope="col">id</th> -->
                               
                                <th class="text-center" scope="col ">name</th>
                                <th class="text-center" scope="col">price</th>
                                <th class="text-center" scope="col">description</th>
                                <th class="text-center" scope="col">category</th>
                                <th class="text-center" scope="col">image</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $query = "SELECT p.id AS product_id, p.name, p.price, p.description,p.image, c.name AS category_name 
                   FROM product p 
                   JOIN category c ON p.category_id = c.id";
                            $products = $conn->query($query);
                            // print_r ($users);
                            foreach ($products as $product) {
                                echo "<tr>
              
                  <td class='text-center'>{$product['name']}</td>
                  <td class='text-center'> {$product['price']}</td>
                  <td class='text-center'> {$product['description']}</td>
                  <td class='text-center'> {$product['category_name']}</td>
                  <td>
                    <div class='text-center'>
                        <img src='" . (!empty($product['image']) ? '../../images/' . $product['image'] : 'images/default.png') . "' width='100' height='60'>
                    </div>
                  </td>
                        <td class='text-center'>
                        <button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editModal' 
                                data-id='{$product['product_id']}' 
                                data-name='{$product['name']}' 
                                data-price='{$product['price']}' 
                                data-description='{$product['description']}' 
                                data-category='{$product['category_name']}'>
                            Edit
                        </button>
                        |
                           <button type='button' class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                                 data-id='{$product['product_id']}'>
                            Delete
                        </button>
                    </td>
                  </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>



                <!-- -------------------------------------------------------------------    ----------------------------------------------------------------------------------- -->


                <!-- Add Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Product</h5>
                                <!-- FIX: Bootstrap 5 requires 'btn-close' instead of 'close' -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form action="insert_data/insert_new.php" method="post" enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <label for="user-name" class="col-form-label"> Name:</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="user-name" class="col-form-label">Price:</label>
                                        <input type="text" class="form-control" id="product_price" name="product_price">
                                    </div>
                                    <div class="mb-3">
                                        <label for="user-name" class="col-form-label">description:</label>
                                        <input type="text" class="form-control" id="product_description"
                                            name="product_description">
                                    </div>
                                    <div class="mb-3">
                                        <label for="user-name" class="col-form-label">category:</label>
                                        <input type="text" class="form-control" id="Product_category"
                                            name="Product_category">
                                    </div>
                                    <div class="text-center">
                                        <input type="file" class="form-control" id="product_img" name="product_img">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary" name="add_product" value="Add">
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- -------------------------------------------------------------------   add ----------------------------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------------  edit  ----------------------------------------------------------------------------------- -->

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="update_data/update.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" id="product_id" value="">
                        <!-- Hidden field for product ID -->

                        <div class="mb-3">
                            <label for="product_name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                value="<?= $product['name'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="product_price" class="col-form-label">Price:</label>
                            <input type="text" class="form-control" id="product_price" name="product_price"
                                value="<?= $product['product_id'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="product_description" class="col-form-label">Description:</label>
                            <input type="text" class="form-control" id="product_description" name="product_description"
                                value="<?= $product['description'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="Product_category" class="col-form-label">Category:</label>
                            <input type="text" class="form-control" id="Product_category" name="Product_category"
                                value="<?= $product['category_name'] ?>">
                        </div>
                        <div class="text-center">
                            <input type="file" class="form-control" id="product_img" name="product_img">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" name="update_product" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- -------------------------------------------------------------------  edit  ----------------------------------------------------------------------------------- -->


    <!-- -------------------------------------------------------------------  delete  ----------------------------------------------------------------------------------- -->


    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                    <form action="delete_data/delete_page.php" method="post">
                        <input type="hidden" name="product_id" id="delete_product_id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-danger" name="delete_product" value="Delete">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------------  delete  ----------------------------------------------------------------------------------- -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>



    <script>
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const productId = button.getAttribute('data-id');
            const productName = button.getAttribute('data-name');
            const productPrice = button.getAttribute('data-price');
            const productDescription = button.getAttribute('data-description');
            const productCategory = button.getAttribute('data-category');

            // Populate the modal fields
            document.getElementById('product_id').value = productId;
            document.getElementById('product_name').value = productName;
            document.getElementById('product_price').value = productPrice;
            document.getElementById('product_description').value = productDescription;
            document.getElementById('Product_category').value = productCategory;
        });
    </script>
    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const productId = button.getAttribute('data-id'); // Get product ID from button

            // Populate the hidden input field with the product ID
            document.getElementById('delete_product_id').value = productId;
        });
    </script>

</body>

</html>