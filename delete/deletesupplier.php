<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../index.php");
    exit();
}

if (!empty($_POST['account_id'])) {
    include '../openconn.php';
    $account_ids = $_POST['account_id'];

    $stmt = mysqli_prepare($conn, "SELECT supplier_id FROM suppliers where account_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    foreach ($account_ids as $id) {

        $stmt = mysqli_prepare($conn, "SELECT supplier_id FROM suppliers where account_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);
        $supplier_id = $row['supplier_id'];

        $stmt1 = mysqli_prepare($conn, "DELETE FROM `transaction` WHERE supplier_id = ?;");
        mysqli_stmt_bind_param($stmt1, "i", $supplier_id);
        mysqli_stmt_execute($stmt1);

        $stmt2 = mysqli_prepare($conn, "DELETE FROM suppliers WHERE supplier_id = ?;");
        mysqli_stmt_bind_param($stmt2, "i", $supplier_id);
        mysqli_stmt_execute($stmt2);

        $stmt3 = mysqli_prepare($conn, "DELETE FROM accounts WHERE account_id = ?;");
        mysqli_stmt_bind_param($stmt3, "i", $id);
        mysqli_stmt_execute($stmt3);
    }

    mysqli_close($conn);

    $_SESSION['deleted'] = "Supplier Deleted Successfully";
    header("location: ../supplier_list.php");
    exit();
} else {
    header("location: ../supplier_list.php");
    exit();
}
