<?php
session_start();
include('../components/connect.php');
header('Content-Type: application/json');

// Error handling
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Authorization check
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized', 401);
    }

    // Validate input
    if (!isset($_GET['order_id']) || !ctype_digit($_GET['order_id'])) {
        throw new Exception('Invalid order ID', 400);
    }

    $orderId = (int)$_GET['order_id'];

    // Get order items
    $stmt = $conn->prepare("SELECT p.name AS product_name, oi.price, oi.quantity 
                           FROM order_item oi
                           JOIN product p ON oi.product_id = p.id
                           WHERE oi.order_id = ?");
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total price
    $stmt = $conn->prepare("SELECT total_price FROM `order` WHERE id = ?");
    $stmt->execute([$orderId]);
    $total = $stmt->fetchColumn();

    if ($total === false) {
        throw new Exception('Order not found', 404);
    }

    echo json_encode([
        'success' => true,
        'items' => $items,
        'total' => (float)$total
    ]);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}