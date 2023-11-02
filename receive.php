<?php
session_start();
if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: login.php");
    exit();
}

function DeleteSched($conn, $id)
{
    $stmt_delete = mysqli_prepare($conn, "DELETE FROM `transaction` where transaction_id = ?");
    mysqli_stmt_bind_param($stmt_delete, "i", $id);
    mysqli_stmt_execute($stmt_delete);
}

if (isset($_GET['id'])) {
    include 'openconn.php';
    $id = $_GET['id'];
    $stmt_transaction = mysqli_prepare($conn, "SELECT quantity, product_id from `transaction` 
    where transaction_id = ?");
    mysqli_stmt_bind_param($stmt_transaction, "i", $id);
    mysqli_stmt_execute($stmt_transaction);
    $result = mysqli_stmt_get_result($stmt_transaction);
    $row = mysqli_fetch_array($result);
    $prodid = $row['product_id'];
    $quantity = $row['quantity'];

    $stmt_stocks = mysqli_prepare($conn, "SELECT stock_id from stocks where product_id = ?");
    mysqli_stmt_bind_param($stmt_stocks, "i", $prodid);
    mysqli_stmt_execute($stmt_stocks);
    $stock_result = mysqli_stmt_get_result($stmt_stocks);
    $stock_row = mysqli_fetch_array($stock_result);

    if (mysqli_num_rows($stock_result) > 0) {
        $stock_id = $stock_row['stock_id'];
        $stmt_stock = mysqli_prepare($conn, "UPDATE stocks SET quantities =quantities + ?, 
        stock_in = stock_in + ? where stock_id = ?;");
        mysqli_stmt_bind_param($stmt_stock, "iii", $quantity, $quantity, $stock_id);
        mysqli_stmt_execute($stmt_stock);
        DeleteSched($conn, $id);
        mysqli_close($conn);
        $_SESSION['receive'] = "Product Received";
        header("location: schedules.php");
        exit();
    } else {
        $zero = 0;
        $stmt_stock = mysqli_prepare($conn, "INSERT INTO stocks(product_id, quantities, stock_in, stock_out)
        VALUES(?,?,?,?);");
        mysqli_stmt_bind_param($stmt_stock, "iiii", $prodid, $quantity, $quantity, $zero);
        mysqli_stmt_execute($stmt_stock);
        DeleteSched($conn, $id);
        mysqli_close($conn);
        $_SESSION['receive'] = "Product Received";
        header("location: schedules.php");
        exit();
    }
} else {
    header("location: schedules.php");
    exit();
}
