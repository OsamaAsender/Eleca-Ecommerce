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
            <a href="../CategoryCRUDS/index.php"> <button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\categories.png" alt="" class="me-1"> Categories</button></a>
            <a href="../ProductCRUDS/index.php">
                <button class="btn CustomSidebarButtons text-white"><img src="../flaticon\product.png" alt=""
                        class="me-1"> Products</button></a>
            <a href="../CouponCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\coupon.png" alt="" class="me-1"> Coupons</button></a>
            <a href="#"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\received.png" alt=""
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
                                    <li>
                                        <a href="../components/profile.php" class="dropdown-item" href="#"><i
                                                class="fa-solid fa-user"></i> Profile</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a href="../components/logout.php?logout=yes" class="logout-btn btn">Log out</a>
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
                <table class="table  table-striped table-hover">
                    <thead class="">
                        <tr>

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
                                <td class="text-center"><?= htmlspecialchars($row['user_name']) ?></td>
                                <td class="text-center">$<?= number_format($row['total_price'], 2) ?></td>
                                <td class="text-center"><?= ucfirst($row['status']) ?></td>
                                <td class="text-center">
                                    <?= $row['coupon_name'] ? htmlspecialchars($row['coupon_name']) : 'N/A' ?>
                                </td>
                                <td class="text-center">
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editStatusModal" data-id="<?= $row['id'] ?>"
                                        data-status="<?= htmlspecialchars($row['status']) ?>">
                                        <!-- Added htmlspecialchars for security -->
                                        Edit
                                    </button>

                                    |

                                    <!-- Details Button -->
                                    <a href="order_details.php?order_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">
                                     Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <!-- Edit Status Modal -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Edit Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStatusForm" method="post" action="update_status.php">
                        <input type="hidden" id="orderId" name="order_id">

                        <div class="mb-3">
                            <label for="orderStatus" class="form-label">Order Status</label>
                            <select class="form-select" id="orderStatus" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="process">Process</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editButtons = document.querySelectorAll(".edit-btn");
            const orderIdField = document.getElementById("orderId");
            const orderStatusField = document.getElementById("orderStatus");

            editButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const orderId = this.getAttribute("data-id");
                    const orderStatus = this.getAttribute("data-status");

                    // Pre-fill the modal fields
                    orderIdField.value = orderId;
                    orderStatusField.value = orderStatus;

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById("editStatusModal"));
                    modal.show();
                });
            });
        });
    </script>





    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="orderDetailsList" class="list-group">
                        <!-- Products will be dynamically loaded here -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const detailButtons = document.querySelectorAll(".details-btn");

            detailButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const orderId = this.getAttribute("data-id");

                    // Make an AJAX call to fetch order details
                    fetch(`fetch_order_details.php?order_id=${orderId}`)
                        .then(response => response.json())
                        .then(data => {
                            const orderDetailsList = document.getElementById("orderDetailsList");
                            orderDetailsList.innerHTML = ""; // Clear previous data

                            if (data.length > 0) {
                                data.forEach(product => {
                                    const listItem = document.createElement("li");
                                    listItem.className = "list-group-item";
                                    listItem.textContent = `${product.name} - Quantity: ${product.quantity}`;
                                    orderDetailsList.appendChild(listItem);
                                });
                            } else {
                                const listItem = document.createElement("li");
                                listItem.className = "list-group-item text-center";
                                listItem.textContent = "No products found for this order.";
                                orderDetailsList.appendChild(listItem);
                            }

                            // Show the modal
                            const modal = new bootstrap.Modal(document.getElementById("detailsModal"));
                            modal.show();
                        })
                        .catch(error => {
                            console.error("Error fetching order details:", error);
                        });
                });
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>