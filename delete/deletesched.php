<?php
session_start();

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: ../login.php");
    exit();
}

if (!empty($_POST['transaction_id'])) {
    include '../openconn.php';
    $item_ids = $_POST['transaction_id'];
    $sql = "DELETE FROM `transaction` WHERE transaction_id = ?;";

    foreach ($item_ids as $item) {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $item);
        mysqli_stmt_execute($stmt);
    }

    $_SESSION['deleted'] = "Schedule Deleted Successfully";
    header("location: ../schedules.php");
    exit();
} else {
    header("location: ../schedules.php");
    exit();
}
