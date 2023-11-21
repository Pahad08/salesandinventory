<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

if (!empty($_POST['account_id'])) {
    include '../openconn.php';
    $account_ids = $_POST['account_id'];

    foreach ($account_ids as $id) {
        $stmt = mysqli_prepare($conn, "DELETE FROM workers WHERE account_id = ?;");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $stmt2 = mysqli_prepare($conn, "DELETE FROM accounts WHERE account_id = ?;");
        mysqli_stmt_bind_param($stmt2, "i", $id);
        mysqli_stmt_execute($stmt2);
    }
    mysqli_close($conn);

    $_SESSION['deleted'] = "Worker Deleted Successfully";
    header("location: ../workers_list.php");
    exit();
} else {
    header("location: ../workers_list.php");
    exit();
}
