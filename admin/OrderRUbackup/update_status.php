<?php
include '../db_connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    // Validate the status value
    if (in_array($status, ['pending', 'process', 'delivered'])) {
        $stmt = $conn->prepare("UPDATE `order` SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Order status updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update order status.";
        }
    } else {
        $_SESSION['error'] = "Invalid status value.";
    }

    $conn = null; // Close the PDO connection
    header("Location: index.php");
    exit;
}
?>
