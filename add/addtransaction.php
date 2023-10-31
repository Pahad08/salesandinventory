<?php
session_start();
include '../openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location:../login.php");
    exit();
}

if (isset($_POST['add'])) {

    $transaction_date = $_POST['transaction_date'];
    $delivery_date = $_POST['delivery_date'];
    $quantity = $_POST['quantity'];
    $supplier_id = $_POST['supplier_id'];
    $product_id = $_POST['product_id'];

    $sql = "INSERT INTO `transaction`(transaction_date, delivery_schedule, quantity, product_id, supplier_id) 
    VALUES(?,?,?,?,?);";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiii", $transaction_date, $delivery_date, $quantity, $product_id, $supplier_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Delivery Schedule Added Successfully";
    mysqli_close($conn);
    header("location: ../schedules.php");
    exit();
} else {
    header("location: ../schedules.php");
    exit();
}
