<?php
include '../db_connection/conn.php';

// Check if the order_id is provided in the query string
if (isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']); // Sanitize the input

    // Fetch the order details
    $stmt = $conn->prepare("SELECT products.name, order_items.quantity, order_items.price 
                            FROM order_items 
                            JOIN products ON order_items.product_id = products.id 
                            WHERE order_items.order_id = :order_id");
    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("No order ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Order Details for Order #<?= htmlspecialchars($orderId) ?></h1>

        <?php if (!empty($orderItems)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                No items found for this order.
            </div>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary">Back to Orders</a>
    </div>
</body>
</html>
