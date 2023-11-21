<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

if (!empty($_POST['account_id'])) {
    include '../openconn.php';
    $account_ids = $_POST['account_id'];
    $null = null;

    foreach ($account_ids as $id) {
        $stmt1 = mysqli_prepare($conn, "UPDATE suppliers SET account_id = ? where account_id = ?;");
        mysqli_stmt_bind_param($stmt1, "si", $null, $id);
        mysqli_stmt_execute($stmt1);


        $stmt2 = mysqli_prepare($conn, "UPDATE workers SET account_id = ? where account_id = ?;");
        mysqli_stmt_bind_param($stmt2, "si", $null, $id);
        mysqli_stmt_execute($stmt2);

        $stmt3 = mysqli_prepare($conn, "DELETE FROM accounts WHERE account_id = ?;");
        mysqli_stmt_bind_param($stmt3, "i", $id);
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
