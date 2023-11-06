<?php
session_start();

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../login.php");
    exit();
}

function CleanData($data)
{
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}


if (isset($_POST['add'])) {
    include '../openconn.php';
    $name = CleanData($_POST['prodid']);
    $quantity = CleanData($_POST['quantities']);
    $stock_out = 0;

    $stmt = mysqli_prepare($conn, "INSERT INTO stocks(product_id, quantities, stock_in, stock_out) 
    VALUES(?,?,?,?);");
    mysqli_stmt_bind_param($stmt, "iiii", $name, $quantity, $quantity, $stock_out);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Stock Added Successfully";
    mysqli_close($conn);
    header("location: ../inventory.php");
    exit();
} else {
    header("location: ../inventory.php");
    exit();
}