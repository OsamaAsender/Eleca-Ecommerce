<?php
include 'conn.php';

if (isset($_POST['update_coupon'])) {
    $coupon_id = $_POST['coupon_id'];
    $name = $_POST['name'];
    $discount_percentage = $_POST['discount_percentage'];
    $start_date = $_POST['start_date'];
    $exp_date = $_POST['exp_date'];

    if ($name && $discount_percentage && $start_date && $exp_date) {
        $update_coupon = $conn->prepare("UPDATE `coupon` SET name = ?, discount_percentage = ?, start_date = ?, exp_date = ? WHERE id = ?");
        $update_coupon->execute([$name, $discount_percentage, $start_date, $exp_date, $coupon_id]);
        header('location:index.php');
    } else {
        echo "All fields are required!";
    }
}
?>
