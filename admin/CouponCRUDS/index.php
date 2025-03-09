<?php
include 'conn.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:user_login.php');
    exit();
}

// Fetch admin profile
$select_admin = $conn->prepare("SELECT * FROM `user` WHERE id = ? AND role = 'admin'");
$select_admin->execute([$user_id]);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #1A2942;
            color: white;
            padding-top: 20px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }
        #sidebar .btn {
            background-color: #1A2942;
            color: white;
            width: 100%;
            text-align: left;
            padding: 15px;
            border: none;
            outline: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        #sidebar .btn:hover {
            background-color: #263b5f;
        }
        #sidebar .sidebarHeaderbutton {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            background-color: #1A2942;
            margin-bottom: 20px;
            padding: 15px;
        }
        #sidebar a {
            text-decoration: none;
        }
        #sidebar img {
            width: 20px;
            margin-right: 10px;
        }
        .container {
            margin-left: 270px;
            padding: 20px;
        }
        .modal {
            display: none; /* Initially hidden */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 30px;
            width: 400px;
            max-width: 90%;
            border-radius: 8px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }
        .close-btn:hover {
            color: black;
        }
        @media (max-width: 768px) {
            #sidebar {
                width: 200px;
            }
            .container {
                margin-left: 220px;
            }
            .modal-content {
                width: 90%;
            }
        }
    </style>
</head>
<body>
<div id="sidebar">
    <button class="btn sidebarHeaderbutton">Dashboard</button>
    <a href="../UserCRUDS/index.php">
        <button class="btn"><img src="../flaticon/man.png" alt=""> Users</button>
    </a>
    <a href="../CategoryCRUDS/index.php">
        <button class="btn"><img src="../flaticon/categories.png" alt=""> Categories</button>
    </a>
    <a href="../ProductCRUDS/index.php">
        <button class="btn"><img src="../flaticon/product.png" alt=""> Products</button>
    </a>
    <a href="../CouponCRUDS">
        <button class="btn"><img src="../flaticon/coupon.png" alt=""> Coupons</button>
    </a>
    <a href="../OrderRU/index.php">
        <button class="btn"><img src="../flaticon/received.png" alt=""> Orders</button>
    </a>
    
</div>

<div class="container mt-5">
    <h1 class="text-center">Manage Coupons</h1>
    <button class="btn btn-primary mb-3" onclick="openModal('add')">Add Coupon</button>

    <table class="table table-hover table-striped">
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

<!-- Add Coupon Modal -->
<div id="addCouponModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('add')">&times;</span>
        <h3>Add Coupon</h3>
        <form method="POST" action="add_coupon.php">
            <input type="text" name="name" placeholder="Coupon Name" class="form-control mb-2" required>
            <input type="number" name="discount_percentage" placeholder="Discount Percentage" class="form-control mb-2" required>
            <input type="date" name="start_date" class="form-control mb-2" required>
            <input type="date" name="exp_date" class="form-control mb-2" required>
            <input type="submit" name="add_coupon" value="Add Coupon" class="btn btn-success">
        </form>
    </div>
</div>

<!-- Edit Coupon Modal -->
<div id="editCouponModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('edit')">&times;</span>
        <h3>Edit Coupon</h3>
        <form method="POST" action="update_coupon.php" ">
        
            <input type="hidden" name="coupon_id" id="edit_coupon_id" >

            <label for="edit_coupon_name">coupon name</label>
            <input type="text" name="name" id="edit_coupon_name" placeholder="Coupon Name" required class="form-control mb-2" required>

            <label for="edit_coupon_discount_percentage">Discount</label>
            <input type="number" name="discount_percentage" id="edit_coupon_discount_percentage" placeholder="Discount Percentage" required class="form-control mb-2" required>

            <label for="edit_coupon_start_date">start_date</label>
            <input type="date" name="start_date" id="edit_coupon_start_date" required class="form-control mb-2" required>

            <label for="edit_coupon_exp_date">exp_date</label>
            <input type="date" name="exp_date" id="edit_coupon_exp_date" required class="form-control mb-2" required>
            

            <input type="submit" name="update_coupon" value="Update Coupon" class="btn btn-primary" class="form-control mb-2" required>
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
            <input type="submit" value="Delete Coupon" class="btn btn-danger">
        </form>
    </div>
</div>
<script>

function openModal(type, id = null, name = '', discount = '', start = '', exp = '') {
    let modal;
    if (type === 'add') {
        modal = document.getElementById('addCouponModal');
    } else if (type === 'edit') {
        document.getElementById('edit_coupon_id').value = id;
        document.getElementById('edit_coupon_name').value = name;
        document.getElementById('edit_coupon_discount_percentage').value = discount;
        document.getElementById('edit_coupon_start_date').value = start;
        document.getElementById('edit_coupon_exp_date').value = exp;
        modal = document.getElementById('editCouponModal');
    } else if (type === 'delete') {
        document.getElementById('delete_coupon_id').value = id;
        modal = document.getElementById('deleteCouponModal');
    }
    
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeModal(type) {
    let modal;
    if (type === 'add') {
        modal = document.getElementById('addCouponModal');
    } else if (type === 'edit') {
        modal = document.getElementById('editCouponModal');
    } else if (type === 'delete') {
        modal = document.getElementById('deleteCouponModal');
    }
    
    if (modal) {
        modal.style.display = 'none';
    }
}

// إغلاق المودال عند الضغط خارج المحتوى
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
};

// تأكد من أن جميع المودالات مخفية عند تحميل الصفحة
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('addCouponModal').style.display = 'none';
    document.getElementById('editCouponModal').style.display = 'none';
    document.getElementById('deleteCouponModal').style.display = 'none';
});
</script>
</body>
</html>
