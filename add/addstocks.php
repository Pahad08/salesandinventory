<?php
session_start();

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../login.php");
    exit();
}


if (isset($_POST['add'])) {
    include '../openconn.php';
    $name = $_POST['prodid'];
    $quantity = $_POST['quantities'];
    $stock_out = 0;

    $statement = mysqli_prepare($conn, "SELECT product_id from stocks where product_id = ?");
    mysqli_stmt_bind_param($statement, "i", $name);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    if (mysqli_num_rows($result) > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE stocks SET quantities = quantities + ?, stock_in = stock_in + ?
        where product_id = ?");
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $quantity, $name);
        mysqli_stmt_execute($stmt);
        $_SESSION['added'] = "Stock Added Successfully";
        mysqli_close($conn);
        header("location: ../inventory.php");
        exit();
    }

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
