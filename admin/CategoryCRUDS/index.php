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
            <a href="../UserCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\man.png" alt=""
            class="me-1"> Users</button></a>
            <a href="#"> <button class="btn CustomSidebarButtons text-white"><img
            src="../flaticon/categories.png" alt="" class="me-1"> Categories</button></a>
            <a href="../ProductCRUDS\index.php">
                <button class="btn CustomSidebarButtons text-white"><img src="../flaticon\product.png" alt=""
                        class="me-1">
                         Products</button>
            </a>
         
           
            <a href="../CouponCRUDS"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\coupon.png" alt=""
                        class="me-1"> Coupons</button></a>
            <a href="../OrderRU/index.php"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\received.png" alt=""
                        class="me-1"> Orders</button></a>

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
                                    <li><a href="../components/profile.php" class="dropdown-item" href=""> Profile</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="../components/logout.php">
                                            Log out</a></li>
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





                <div class="container mt-5">
                    <div class="mb-5">
                        <h1>Category Information</h1>
                        <!-- FIX: Updated data attributes for Bootstrap 5 -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Add Category
                        </button>
                    </div>

                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <!-- <th scope="col">id</th> -->
                                <th scope="col" class="text-center">Id</th>
                                <th scope="col" class="text-center">name</th>
                                <th scope="col" class="text-center">image</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM `category`";
                            $categories = $conn->query($query);
                            // print_r ($users);
                            foreach ($categories as $category) {
                                echo "<tr>
                   <td class='text-center'>{$category['id']}</td>
                  <td class='text-center'>{$category['name']}</td>
                  <td>
                    <div class='text-center'>
                        <img src='" . (!empty($category['image']) ? 'images/' . $category['image'] : 'images/default.png') . "' width='100' height='60'>
                    </div>
                  </td>
                  <td class='text-center'>
                  <a href='./update_data/update_page.php?id={$category['id']}' class='btn btn-sm btn-primary'><i class='fa-solid fa-pen-to-square'></i> Edit</a>
                  |
                  <a href='./delete_data/delete_page.php?id={$category['id']}' class='btn btn-sm btn-danger'><i class='fa-solid fa-trash'></i> Delete</a>
                  </td>

                  </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
                                <!-- FIX: Bootstrap 5 requires 'btn-close' instead of 'close' -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form action="insert_data/insert_new.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="user-name" class="col-form-label"> Name:</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name">
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" class="form-control" name="category_image">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="add_category" value="Add">
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </main>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
    