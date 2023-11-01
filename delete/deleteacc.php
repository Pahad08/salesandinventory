<?php
session_start();

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: ../login.php");
    exit();
}

if (!empty($_POST['account_id'])) {
    include '../openconn.php';
    $account_ids = $_POST['account_id'];

    foreach ($account_ids as $id) {
        $stmt1 = mysqli_prepare($conn, "DELETE FROM suppliers WHERE account_id = ?;");
        $stmt2 = mysqli_prepare($conn, "DELETE FROM workers WHERE account_id = ?;");
        $stmt3 = mysqli_prepare($conn, "DELETE FROM accounts WHERE account_id = ?;");
        mysqli_stmt_bind_param($stmt1, "i", $id);
        mysqli_stmt_bind_param($stmt2, "i", $id);
        mysqli_stmt_bind_param($stmt3, "i", $id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_execute($stmt3);
    }
    mysqli_close($conn);

    $_SESSION['deleted'] = "Account Deleted Successfully";
    header("location: ../users.php");
    exit();
} else {
    header("location: ../users.php");
    exit();
}
