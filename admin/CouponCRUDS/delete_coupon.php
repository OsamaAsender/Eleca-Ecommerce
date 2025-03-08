<?php
include 'conn.php';

if (isset($_GET['delete'])) {
    $coupon_id = $_GET['delete'];
    $delete_coupon = $conn->prepare("DELETE FROM `coupon` WHERE id = ?");
    $delete_coupon->execute([$coupon_id]);
    header('location:index.php');
}
?>
