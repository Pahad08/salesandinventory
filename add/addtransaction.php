<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

if (isset($_POST['add'])) {
    include '../openconn.php';

    $delivery_date = $_POST['delivery_date'];
    $quantity = $_POST['quantity'];
    $supplier_id = $_POST['supplier_id'];
    $product_id = $_POST['product_id'];

    $sql = "INSERT INTO `transaction`(transaction_date, delivery_schedule, quantity, product_id, supplier_id) 
    VALUES(CURRENT_DATE(),?,?,?,?);";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siii", $delivery_date, $quantity, $product_id, $supplier_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Schedule Added Successfully";
    mysqli_close($conn);
    header("location: ../schedules.php");
    exit();
} else {
    header("location: ../schedules.php");
    exit();
}
