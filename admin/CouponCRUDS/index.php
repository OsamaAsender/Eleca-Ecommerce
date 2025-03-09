<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['user_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit();
}

// Fetch admin profile
$select_admin = $conn->prepare("SELECT * FROM `user` WHERE id = ? AND role = 'admin'");
$select_admin->execute([$admin_id]);
$fetch_profile = $select_admin->fetch(PDO::FETCH_ASSOC);

// Fetch all coupons
$select_coupons = $conn->prepare("SELECT * FROM `coupon`");
$select_coupons->execute();
$coupons = $select_coupons->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons</title>
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
<div class="d-flex">
        <div id="sidebar">
            <button class="btn text-white sidebarHeaderbutton">Dashboard</button>
            <a href="../UserCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\man.png" alt=""
            class="me-1"> Users</button></a>
            <a href="../CategoryCRUDS/index.php"> <button class="btn CustomSidebarButtons text-white"><img
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
                    <div class="d-flex">
                    <button class="btn fw-bold">ELECA SHOP</button>
                    </div>

                    <div class="me-5 pe-4">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="../flaticon/profile.png" alt="asdfsadf">
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="../components/profile.php" class="dropdown-item" href=""><i class="fa-solid fa-user"></i> Profile</a>
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
<!-- Main content area -->
<section class="container mt-5">
    <h1 class="heading">Manage Coupons</h1>
    <div class="box-container">
        <div class="box mb-5">
            <button class="btn btn-primary" onclick="openModal('add')">Add Coupon</button>
        </div>

        <div class="box">

            <table class="table table-hover table-striped ">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Discount (%)</th>
                        <th>Start Date</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $coupon): ?>
                        <tr>
                            <td><?= htmlspecialchars($coupon['name']); ?></td>
                            <td><?= $coupon['discount_percentage']; ?></td>
                            <td><?= $coupon['start_date']; ?></td>
                            <td><?= $coupon['exp_date']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="openModal('edit', <?= $coupon['id']; ?>, '<?= htmlspecialchars($coupon['name']); ?>', <?= $coupon['discount_percentage']; ?>, '<?= $coupon['start_date']; ?>', '<?= $coupon['exp_date']; ?>')">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="openModal('delete', <?= $coupon['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modals -->
<!-- Add Coupon Modal -->
<div id="addCouponModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('add')">&times;</span>
        <h3>Add Coupon</h3>
        <form method="POST" action="add_coupon.php">
            <input type="text" name="name" placeholder="Coupon Name" required>
            <input type="number" name="discount_percentage" placeholder="Discount Percentage" required>
            <input type="date" name="start_date" required>
            <input type="date" name="exp_date" required>
            <input type="submit" name="add_coupon" value="Add Coupon" class="btn">
        </form>
    </div>
</div>

<!-- Edit Coupon Modal -->
<div id="editCouponModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('edit')">&times;</span>
        <h3>Edit Coupon</h3>
        <form method="POST" action="update_coupon.php">
            <input type="hidden" name="coupon_id" id="edit_coupon_id">
            <input type="text" name="name" id="edit_coupon_name" placeholder="Coupon Name" required>
            <input type="number" name="discount_percentage" id="edit_coupon_discount_percentage" placeholder="Discount Percentage" required>
            <input type="date" name="start_date" id="edit_coupon_start_date" required>
            <input type="date" name="exp_date" id="edit_coupon_exp_date" required>
            <input type="submit" name="update_coupon" value="Update Coupon" class="btn">
        </form>
    </div>
</div>

<!-- Delete Coupon Modal -->
<div id="deleteCouponModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('delete')">&times;</span>
        <h3>Are you sure you want to delete this coupon?</h3>
        <form method="GET" action="delete_coupon.php">
            <input type="hidden" name="delete" id="delete_coupon_id">
            <input type="submit" value="Delete Coupon" class="btn" style="background-color: red;">
        </form>
    </div>
</div>
</section>

</div>



<script src="js/admin_script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
