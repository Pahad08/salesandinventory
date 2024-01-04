<?php
session_start();

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


if (isset($_POST['add'])) {
    include '../openconn.php';
    $name = CleanData($conn, $_POST['product']);
    $kilogram = $_POST['kilogram'];
    $price = $_POST['price'];

    $stmt_name = mysqli_prepare($conn, "SELECT `name` from products where `name` = ?");
    mysqli_stmt_bind_param($stmt_name, "s", $name);
    mysqli_stmt_execute($stmt_name);
    $result = mysqli_stmt_get_result($stmt_name);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['exist'] = "Product Already Exist";
        mysqli_close($conn);
        header("location: ../products.php");
        exit();
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO products(`name`, kilogram, price) VALUES(?,?,?);");
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
