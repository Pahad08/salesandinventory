<?php
session_start();

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../login.php");
    exit();
}

if (!empty($_POST['stock_id'])) {
    include '../openconn.php';
    $stock_ids = $_POST['stock_id'];

    foreach ($stock_ids as $id) {
        $stmt = mysqli_prepare($conn, "DELETE FROM stocks WHERE stock_id = ?;");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    mysqli_close($conn);

    $_SESSION['deleted'] = "Stock Deleted Successfully";
    header("location: ../inventory.php");
    exit();
} else {
    header("location: ../inventory.php");
    exit();
}