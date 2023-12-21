<?php
session_start();
include '../openconn.php';

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../index.php");
    exit();
}

function CleanData($conn, $data)
{
    $data = stripslashes($data);
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $name = CleanData($conn, $_POST['product']);
    $kilogram = $_POST['kilogram'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET `name`=?, kilogram = ?, price = ? where product_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siii", $name, $kilogram, $price, $id);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Product Updated Successfully";
    mysqli_close($conn);
    header("location: ../products.php");
    exit();
} else {
    header("location: ../products.php");
    exit();
}
