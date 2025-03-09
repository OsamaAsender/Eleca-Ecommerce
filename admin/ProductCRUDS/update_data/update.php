<?php
// Include the database connection file
require_once '../db_connection/conn.php'; // Path to the database connection
$upload_dir = '../images/'; // Path to the image directory

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    // Retrieve data from the form
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $product_category = $_POST['Product_category'];

    // Handle the uploaded image
    $image_name = '';
    if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['product_img']['tmp_name'];
        $image_name = basename($_FILES['product_img']['name']);
        move_uploaded_file($tmp_name, $upload_dir . $image_name);
    } else {
        // If no new image is uploaded, keep the old one
        $image_name = $_POST['current_image'];
    }

    try {
        // Fetch the category ID if needed
        $category_stmt = $conn->prepare("SELECT id FROM category WHERE name = :name");
        $category_stmt->execute([':name' => $product_category]);
        $category_row = $category_stmt->fetch(PDO::FETCH_ASSOC);

        if ($category_row) {
            $category_id = $category_row['id'];

            // Update query
            $sql = "UPDATE product 
                    SET name = :name, price = :price, description = :description, category_id = :category_id, image = :image 
                    WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $product_name,
                ':price' => $product_price,
                ':description' => $product_description,
                ':category_id' => $category_id,
                ':image' => $image_name,
                ':id' => $product_id
            ]);

            // Redirect or display success message
            header("Location: ../index.php");
            exit;
        } else {
            echo "Error: Category not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
