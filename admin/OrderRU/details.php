<?php
include 'db_connection/conn.php';

if (isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    
    $stmt = $conn->prepare("SELECT products.name, order_items.quantity 
                            FROM order_items 
                            JOIN products ON order_items.product_id = products.id 
                            WHERE order_items.order_id = :order_id");
    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    echo json_encode([]);
}
?>
