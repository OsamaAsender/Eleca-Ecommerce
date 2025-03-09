<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

include('../components/connect.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_order'])) {
        $order_id = $_POST['order_id'];
        try {
            $conn->beginTransaction();
            
            // Delete order items first
            $stmt = $conn->prepare("DELETE FROM order_item WHERE order_id = ?");
            $stmt->execute([$order_id]);
            
            // Then delete the order
            $stmt = $conn->prepare("DELETE FROM `order` WHERE id = ?");
            $stmt->execute([$order_id]);
            
            $conn->commit();
            $_SESSION['message'] = "Order deleted successfully!";
        } catch (PDOException $e) {
            $conn->rollBack();
            $_SESSION['error'] = "Error deleting order: " . $e->getMessage();
        }
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];
        try {
            $stmt = $conn->prepare("UPDATE `order` SET status = ? WHERE id = ?");
            $stmt->execute([$status, $order_id]);
            $_SESSION['message'] = "Order status updated successfully!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating order: " . $e->getMessage();
        }
        header("Location: index.php");
        exit();
    }
}

// Fetch orders
$query = "SELECT o.id, u.name AS user_name, o.total_price, o.status, c.name AS coupon_name 
          FROM `order` o
          LEFT JOIN `user` u ON o.user_id = u.id
          LEFT JOIN `coupon` c ON o.coupon_id = c.id
          ORDER BY o.id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Orders Management</title>
    <style>
        .order-status {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Your existing sidebar code -->
        <div id="sidebar">
            <button class="btn text-white sidebarHeaderbutton">Dashboard</button>
            <a href="../UserCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\man.png" alt="" class="me-1"> Users</button></a>
            <a href="#"> <button class="btn CustomSidebarButtons text-white"><img src="../flaticon/categories.png"
                        alt="" class="me-1"> Categories</button></a>
            <a href="../ProductCRUDS\index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\product.png" alt="" class="me-1"> Products</button></a>
            <a href="../CouponCRUDS"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\coupon.png" alt="" class="me-1"> Coupons</button></a>
            <a href="../OrderRU/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\received.png" alt="" class="me-1"> Orders</button></a>
        </div>
        <div class="page-content ">
            <!-- Your existing navbar code -->

            <div class=" mt-4">
                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class=" shadow-sm">
                    <div class="">
                        <h4 class="mb-0">Orders Management</h4>
                    </div>
                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Coupon</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td><?= htmlspecialchars($order['user_name']) ?></td>
                                            <td>$<?= number_format($order['total_price'], 2) ?></td>
                                            <td>
                                                <span class="order-status status-<?= $order['status'] ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $order['coupon_name'] ? htmlspecialchars($order['coupon_name']) : 'None' ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary details-btn" 
                                                        data-id="<?= $order['id'] ?>">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success edit-btn" 
                                                        data-id="<?= $order['id'] ?>"
                                                        data-status="<?= $order['status'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                        data-id="<?= $order['id'] ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="orderItems" class="mb-3"></div>
                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                        <h5 class="mb-0">Total Amount:</h5>
                        <h4 class="mb-0 text-success" id="orderTotal"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Update Order Status</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="editOrderId">
                        <div class="mb-3">
                            <label class="form-label">Select Status</label>
                            <select name="status" class="form-select" id="editStatus" required>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_order" class="btn btn-success">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="deleteOrderId">
                        <p class="lead">Are you sure you want to delete this order? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_order" class="btn btn-danger">Delete Permanently</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Details Modal
    document.querySelectorAll('.details-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const orderId = btn.dataset.id;
        const modal = new bootstrap.Modal('#detailsModal');
        const itemsContainer = document.getElementById('orderItems');
        const totalElement = document.getElementById('orderTotal');

        try {
            itemsContainer.innerHTML = '<div class="text-center">Loading...</div>';
            
            const response = await fetch(`get_order_details.php?order_id=${orderId}`);
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error);
            }

            if (data.items.length === 0) {
                itemsContainer.innerHTML = '<div class="text-center text-muted">No items found</div>';
            } else {
                itemsContainer.innerHTML = data.items.map(item => `
                    <div class="card mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">${item.product_name}</h6>
                                <small class="text-muted">Price: $${item.price}</small>
                            </div>
                            <div>
                                <span class="badge bg-primary rounded-pill">Qty: ${item.quantity}</span>
                                <span class="ms-2 fw-bold">$${(item.price * item.quantity).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            totalElement.textContent = data.total.toFixed(2);
            modal.show();

        } catch (error) {
            console.error('Error:', error);
            itemsContainer.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
            totalElement.textContent = '0.00';
        }
    });
});

        // Edit Modal
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('editOrderId').value = btn.dataset.id;
                document.getElementById('editStatus').value = btn.dataset.status;
                new bootstrap.Modal('#editModal').show();
            });
        });

        // Delete Modal
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('deleteOrderId').value = btn.dataset.id;
                new bootstrap.Modal('#deleteModal').show();
            });
        });
    </script>
</body>
</html>