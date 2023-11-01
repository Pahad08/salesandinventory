<?php
session_start();
include '../openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: ../login.php");
    exit();
}

function CleanData($data)
{
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}

if (isset($_POST['edit'])) {

    $transaction_id = CleanData($_POST['transaction_id']);
    $transaction_date = CleanData($_POST['transaction_date']);
    $delivery_date = CleanData($_POST['delivery_date']);
    $quantity = CleanData($_POST['quantity']);
    $supplier_id = CleanData($_POST['supplier_id']);
    $product_id = CleanData($_POST['product_id']);

    $sql = "UPDATE `transaction` SET transaction_date=?, delivery_schedule = ?, quantity = ?, 
    product_id = ?, supplier_id = ? where transaction_id= ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiiii", $transaction_date, $delivery_date, $quantity, $product_id, $supplier_id, $transaction_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Schedule Updated Successfully";
    mysqli_close($conn);
    header("location: ../schedules.php");
    exit();
} else {
    header("location: ../schedules.php");
    exit();
}
