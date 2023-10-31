<?php
session_start();
include '../openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location:../login.php");
    exit();
}

function CleanData($data)
{
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}


if (isset($_POST['add'])) {

    $name = CleanData($_POST['product']);
    $kilogram = CleanData($_POST['kilogram']);
    $price = CleanData($_POST['price']);

    $sql = "INSERT INTO products(`name`, kilogram, price) VALUES(?,?,?);";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $name, $kilogram, $price);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Product Added Successfully";
    mysqli_close($conn);
    header("location: ../products.php");
    exit();
} else {
    header("location: ../products.php");
    exit();
}