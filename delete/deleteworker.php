<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

if (!empty($_POST['worker_id'])) {
    include '../openconn.php';
    $worker_ids = $_POST['worker_id'];

    foreach ($worker_ids as $id) {
        $stmt = mysqli_prepare($conn, "DELETE FROM workers WHERE worker_id = ?;");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    mysqli_close($conn);

    $_SESSION['deleted'] = "Worker Deleted Successfully";
    header("location: ../workers_list.php");
    exit();
} else {
    header("location: ../workers_list.php");
    exit();
}