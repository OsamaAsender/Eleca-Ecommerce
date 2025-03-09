<?php
// Include the database connection file
require_once '../db_connection/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    try {
        // Delete query
        $sql = "DELETE FROM product WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $product_id]);

        // Redirect or display success message
        header("Location: ../index.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
