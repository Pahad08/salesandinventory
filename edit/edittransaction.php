<?php
session_start();
include '../openconn.php';

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

if (isset($_POST['edit'])) {

    $transaction_id = $_POST['transaction_id'];
    $delivery_date = $_POST['delivery_date'];
    $quantity = $_POST['quantity'];
    $supplier_id = $_POST['supplier_id'];
    $product_id = $_POST['product_id'];

    $sql = "UPDATE `transaction` SET delivery_schedule = ?, quantity = ?, 
    product_id = ?, supplier_id = ? where transaction_id= ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siiii", $delivery_date, $quantity, $product_id, $supplier_id, $transaction_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Schedule Updated Successfully";
    mysqli_close($conn);
    header("location: ../schedules.php");
    exit();
} else {
    header("location: ../schedules.php");
    exit();
}