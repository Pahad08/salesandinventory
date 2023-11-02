<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: login.php");
    exit();
}

if (!empty($_POST['product_id'])) {
    include '../openconn.php';
    $product_ids = $_POST['product_id'];

    foreach ($product_ids as $id) {
        $stmt1 = mysqli_prepare($conn, "DELETE FROM stocks WHERE product_id = ?;");
        $stmt2 = mysqli_prepare($conn, "DELETE FROM sales WHERE product_id = ?;");
        $stmt3 = mysqli_prepare($conn, "DELETE FROM `transaction` WHERE product_id = ?;");
        $stmt4 = mysqli_prepare($conn, "DELETE FROM products WHERE product_id = ?;");
        mysqli_stmt_bind_param($stmt1, "i", $id);
        mysqli_stmt_bind_param($stmt2, "i", $id);
        mysqli_stmt_bind_param($stmt3, "i", $id);
        mysqli_stmt_bind_param($stmt4, "i", $id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_execute($stmt4);
    }
    mysqli_close($conn);

    $_SESSION['deleted'] = "Product Deleted Successfully";
    header("location: ../products.php");
    exit();
} else {
    header("location: ../products.php");
    exit();
}
