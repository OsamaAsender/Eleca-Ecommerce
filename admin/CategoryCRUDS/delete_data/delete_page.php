<?php
// Include the database connection file
require_once '../db_connection/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];

    try {
        // Delete query
        $sql = "DELETE FROM category WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $category_id]);

        // Redirect or display success message
        header("Location: ../index.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
