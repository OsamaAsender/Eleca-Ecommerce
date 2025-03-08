<?php
include 'conn.php';

if (isset($_POST['add_coupon'])) {
    $name = $_POST['name'];
    $discount_percentage = $_POST['discount_percentage'];
    $start_date = $_POST['start_date'];
    $exp_date = $_POST['exp_date'];

    if ($name && $discount_percentage && $start_date && $exp_date) {
        $insert_coupon = $conn->prepare("INSERT INTO `coupon` (name, discount_percentage, start_date, exp_date) VALUES (?, ?, ?, ?)");
        $insert_coupon->execute([$name, $discount_percentage, $start_date, $exp_date]);
        header('location:index.php');
    } else {
        echo "All fields are required!";
    }
}
?>
