<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit();
}

if (isset($_POST['delete'])) {
    $wishlist_item_id = $_POST['wishlist_item_id'];
    $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist_item` WHERE id = ?");
    $delete_wishlist_item->execute([$wishlist_item_id]);
}

if (isset($_GET['delete_all'])) {
    $delete_wishlist_items = $conn->prepare("DELETE FROM `wishlist_item` WHERE wishlist_id IN (SELECT id FROM `wishlist` WHERE user_id = ?)");
    $delete_wishlist_items->execute([$user_id]);
    header('location:wishlist.php');
    exit();
}


if (isset($_POST['add_to_order'])) {
    $wishlist_item_id = $_POST['wishlist_item_id'];
    $select_wishlist_item = $conn->prepare("SELECT * FROM wishlist_item WHERE id = ?");
    $select_wishlist_item->execute([$wishlist_item_id]);
    $item = $select_wishlist_item->fetch(PDO::FETCH_ASSOC);
    
    if ($item) {
        $id = $item['id'];
        $product_id = $item['product_id'];
        $select_product = $conn->prepare("SELECT * FROM product WHERE id = ?");
        $select_product->execute([$product_id]);
        
        $product = $select_product->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $check_order = $conn->prepare("SELECT * FROM `order` WHERE user_id = ? AND status = 'pending'");
            $check_order->execute([$user_id]);
            $order = $check_order->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                $insert_order = $conn->prepare("INSERT INTO `order` (user_id, total_price, status) VALUES (?, ?, 'pending')");
                $insert_order->execute([$user_id, $product['price']]);
                $order_id = $conn->lastInsertId();
            } else {
                $order_id = $order['id'];
                $check_order_item = $conn->prepare("SELECT * FROM order_item WHERE order_id = ? AND product_id = ?");
                $check_order_item->execute([$order_id, $product_id]);
                $order_item = $check_order_item->fetch(PDO::FETCH_ASSOC);
                
                if ($order_item) {
                    $update_order_item = $conn->prepare("UPDATE order_item SET quantity = quantity + 1 WHERE order_id = ? AND product_id = ?");
                    $update_order_item->execute([$order_id, $product_id]);
                } else {
                    $insert_order_item = $conn->prepare("INSERT INTO order_item (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)");
                    $insert_order_item->execute([$order_id, $product_id, $product['price'], 1]);
                }
            }
            
            $delete_wishlist_item = $conn->prepare("DELETE FROM wishlist_item WHERE id = ?");
            $delete_wishlist_item->execute([$wishlist_item_id]);
            
            header('location:wishlist.php?added_success=true');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .wishlist-container {
            max-width: 1200px;  
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        table {
            width: 95%;  
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            font-size: 16px;
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background:#2980b9;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .wishlist-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .wishlist-item button {
            padding: 10px 15px; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 15px; 
            margin-top: 5px;    
            transition: all 0.3s ease; 
        }
        .wishlist-item button:hover {
            border-radius: 10px; 
            transform: scale(1.05); 
        }
        .delete-btn {
            background-color: red;
            color: white;
        }
        .add-btn {
            margin-left: 20px;
            background-color: green;
            color: white;
        }
        .delete-btn:hover {
            background-color: rgb(84, 6, 6); 
        }
        .clear-all-btn {
            background: linear-gradient(135deg, #e60000, #c0392b);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            float: right;
            margin: 10px 0;
            margin-right: 60px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .clear-all-btn:hover {
            background: linear-gradient(135deg, #c0392b, #e60000);
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .clear-all-btn {
                margin-right: 20px;
                padding: 8px 15px;
            }
        }
        @media (max-width: 480px) {
            .clear-all-btn {
                margin-right: 10px;
                padding: 6px 12px;
                font-size: 14px;
            }
        }
        .clear-all-btn:hover {
            background-color: rgb(84, 6, 6); 
        }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="wishlist-container">
   
    
    <button id="deleteAllBtn" class="clear-all-btn">Delete All Items</button>

    <table>
        <thead>
            <tr>
                
                <th>Product Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
$select_wishlist = $conn->prepare("SELECT wi.*, p.name, p.price FROM wishlist_item wi JOIN product p ON wi.product_id = p.id WHERE wi.wishlist_id IN (SELECT id FROM wishlist WHERE user_id = ?)");
$select_wishlist->execute([$user_id]);

                if ($select_wishlist->rowCount() > 0) {
                    while ($item = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr class="wishlist-item">';
                       
                        echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                        echo '<td>JD' . htmlspecialchars($item['price']) . '</td>';
                        echo '<td>';
                        echo '<form action="" method="POST" style="display:inline-block;" class="delete-form">';
                        echo '<input type="hidden" name="wishlist_item_id" value="' . $item['id'] . '">';
                        echo '<button type="submit" name="delete" class="delete-btn">Delete</button>';
                        echo '</form>';
                        echo '<form action="" method="POST" style="display:inline-block;">';
                        echo '<input type="hidden" name="wishlist_item_id" value="' . $item['id'] . '">';
                        echo '<button type="submit" name="add_to_order" class="add-btn">Add to Order</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">Your wishlist is empty.</td></tr>';
                }
            ?>
        </tbody>
    </table>
</div>

<?php include 'components/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   $(document).ready(function(){
      $('#deleteAllBtn').click(function(e){
         e.preventDefault();
         Swal.fire({
            title: 'Are you sure?',
            text: "This will delete all items from your wishlist.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete all!'
         }).then((result) => {
            if (result.isConfirmed) {
               window.location.href = "wishlist.php?delete_all=true";
            }
         });
      });
   });
</script>

<script src="js/script.js"></script>

</body>
</html>
