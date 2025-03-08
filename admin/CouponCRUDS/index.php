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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/manage_coupons.css">
    <style>
        /* Sidebar Styles */
        ul {
            list-style-type: none;
        }
        #sidebar {
            width: 15%;
            height: 100vh;
            background-color: #1A2942;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 1rem;
        }
        .CustomSidebarButtons {
            width: 100%;
            padding: 1rem;
            text-align: start;
            border-radius: 0;
            font-weight: 500;
            background: none;
            color: white;
            border: none;
            text-decoration: none;
        }
        .CustomSidebarButtons:hover {
            background-color: #263b5f;
        }
        .sidebarHeaderbutton {
            width: 100%;
            padding: 1rem;
            font-size: 2rem;
            border-radius: 0;
            font-weight: 500;
            background: none;
            color: white;
            border: none;
            text-decoration: none;
        }
        nav {
            width: 85vw;
            margin-left: 15%;
            padding: 1rem;
        }
        .navbarEnd {
            margin: 1rem;
            width: 3rem;
        }
        /* Ensure the main content isn't hidden behind the sidebar */
        section.dashboard {
            margin-left: 15%;
            padding: 1rem;
        }
        .box-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <button class="btn sidebarHeaderbutton">Admin Dashboard</button>
    <a href="../UserCRUDS/index.php"><button class="btn CustomSidebarButtons"><img src="../flaticon/man.png" alt="" class="me-1"> Users</button></a>
    <a href="../CategoryCRUDS/index.php"><button class="btn CustomSidebarButtons"><img src="../flaticon/categories.png" alt="" class="me-1"> Categories</button></a>
    <a href="../ProductCRUDS/index.php"><button class="btn CustomSidebarButtons"><img src="../flaticon/product.png" alt="" class="me-1"> Products</button></a>
    <a href="../CouponCRUDS/index.php"><button class="btn CustomSidebarButtons"><img src="../flaticon/coupon.png" alt="" class="me-1"> Coupons</button></a>
    <a href="../OrderRU/index.php"><button class="btn CustomSidebarButtons"><img src="../flaticon/received.png" alt="" class="me-1"> Orders</button></a>
    <a href=""><button class="btn CustomSidebarButtons"><img src="../flaticon/cogwheel.png" alt="" class="me-1"> Settings</button></a>
</div>

<!-- Main content area -->
<section class="dashboard">
    <h1 class="heading">Manage Coupons</h1>
    <div class="box-container">
        <div class="box">
            <h3>Add New Coupon</h3>
            <button class="btn" onclick="openModal('add')">Add Coupon</button>
        </div>

        <div class="box">
            <h3>Coupons List</h3>
            <table>
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
                                <button class="btn" onclick="openModal('edit', <?= $coupon['id']; ?>, '<?= htmlspecialchars($coupon['name']); ?>', <?= $coupon['discount_percentage']; ?>, '<?= $coupon['start_date']; ?>', '<?= $coupon['exp_date']; ?>')">Edit</button>
                                <button class="btn" onclick="openModal('delete', <?= $coupon['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

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

<script src="js/admin_script.js"></script>
</body>
</html>
