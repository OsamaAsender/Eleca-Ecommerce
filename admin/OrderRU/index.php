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
            <button class="btn text-white sidebarHeaderbutton">Eleca Shop</button>
            <a href=""><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\man.png" alt=""
                        class="me-1"> Users</button></a>
            <a href="../CategoryCRUDS/index.php"> <button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\categories.png" alt="" class="me-1"> Categories</button></a>
            <a href="../ProductCRUDS/index.php">
                <button class="btn CustomSidebarButtons text-white"><img src="../flaticon\product.png" alt=""
                        class="me-1"> Products</button></a>
            <a href=""><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\coupon.png" alt=""
                        class="me-1"> Coupons</button></a>
            <a href="#"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\received.png" alt=""
                        class="me-1"> Orders</button></a>
            <a href=""><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\cogwheel.png" alt=""
                        class="me-1"> Settings</button></a>

        </div>
        <div class="page-content">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="d-flex">
                        <button class="btn"><i class="fa-solid fa-bars"></i></button>
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>


                    <div class="me-5 pe-4">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                            <li class="mx-3 pt-2"> <a href="#"><img src="../flaticon/notification.png"
                                        alt="asdfsadf"></a></li>

                            <li class="mx-3 pt-2"><a href="#"><a href="#"><img src="../flaticon/envelope.png"
                                            alt="asdfsadf"></a></li>

                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="../flaticon/profile.png" alt="asdfsadf">
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"><i class="fa-solid fa-gear"></i> Settings</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket"></i>
                                            Log out
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                    </div>

                </div>

            </nav>

            <?php

            include('../components/connect.php');

            if (isset($_GET['message'])) {
                echo "<div style='background-color:green'> {$_GET['message']}</div>";
            }
            ?>


            <?php

            include '../components/connect.php';

            // Fetch orders from database using PDO
            $query = "SELECT o.id, u.name AS user_name, o.total_price, o.status, c.name AS coupon_name FROM `order` o
          LEFT JOIN `user` u ON o.user_id = u.id
          LEFT JOIN `coupon` c ON o.coupon_id = c.id
          ORDER BY o.id DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="container mt-5">
                <h2 class="mb-4">Orders Management</h2>
                <table class="table table-bordered table-striped table-hover">
                    <thead class="">
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Total Price</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Coupon</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $row): ?>
                            <tr>
                                <td class="text-center"><?= $row['id'] ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['user_name']) ?></td>
                                <td class="text-center">$<?= number_format($row['total_price'], 2) ?></td>
                                <td class="text-center"><?= ucfirst($row['status']) ?></td>
                                <td class="text-center">
                                    <?= $row['coupon_name'] ? htmlspecialchars($row['coupon_name']) : 'N/A' ?></td>
                                <td class="text-center">
                                    <a href="edit_order.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm"> <i
                                            class="fa-solid fa-pen-to-square"></i> Edit</a>
                                    |
                                    <a href="update_order.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"><i
                                            class='fa-solid fa-trash'></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>

</html>