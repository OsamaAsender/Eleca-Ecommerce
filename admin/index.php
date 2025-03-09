<?php 
$admin_id = $_SESSION['admin_id'] ?? null;
$user_role = null;

// Get current user's role
if ($admin_id) {
    try {
        $stmt = $conn->prepare("SELECT role FROM `user` WHERE id = ?");
        $stmt->execute([$admin_id]);
        $user_role = $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error fetching user role: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <!-- sidebar -->

    <div class="d-flex">
        <div id="sidebar">
            <button class="btn text-white sidebarHeaderbutton">Dashboard</button>

            <?php if ($user_role === 'super admin'): ?>
                <a href="../UserCRUDS/index.php">
                    <button class="btn CustomSidebarButtons text-white">
                        <img src="../flaticon/man.png" alt="" class="me-1"> Users
                    </button>
                </a>
            <?php endif; ?>

            <a href="CategoryCRUDS/index.php"> <button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon/categories.png" alt="" class="me-1"> Categories</button></a>
            <a href="ProductCRUDS\index.php">
                <button class="btn CustomSidebarButtons text-white"><img src="../flaticon\product.png" alt=""
                        class="me-1">
                    Products</button>
            </a>


            <a href="CouponCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\coupon.png" alt=""
                        class="me-1"> Coupons</button></a>
            <a href="OrderRU/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\received.png" alt="" class="me-1"> Orders</button></a>
            <a href=""><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\cogwheel.png" alt=""
                        class="me-1"> Settings</button></a>

        </div>
        <div class="page-content">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="d-flex">
                        <button class="btn"><i class="fa-solid fa-bars"></i></button>
                        
                    </div>


                    <div class="me-5 pe-4">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="../flaticon/profile.png" alt="asdfsadf">
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href='../components/profile.php' class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a>
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

            <div class="container mt-5">
                <h1>Welcome Admin Dashboard</h1>
            </div>