<?php
session_start();

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../index.php");
    exit();
}

if (!empty($_POST['sale_id'])) {
    include '../openconn.php';
    $sale_ids = $_POST['sale_id'];

    foreach ($sale_ids as $id) {
        $stmt = mysqli_prepare($conn, "DELETE FROM sales WHERE sale_id = ?;");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    mysqli_close($conn);
    $_SESSION['deleted'] = "Sales Deleted Successfully";
    header("location: ../sales.php");
    exit();
} else {
    header("location: ../sales.php");
    exit();
}
