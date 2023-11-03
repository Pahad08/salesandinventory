<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: login.php");
    exit();
}

if (!empty($_POST['supplier_id'])) {
    include '../openconn.php';
    $supplier_ids = $_POST['supplier_id'];

    foreach ($supplier_ids as $id) {
        $stmt1 = mysqli_prepare($conn, "DELETE FROM `transaction` WHERE supplier_id = ?;");
        mysqli_stmt_bind_param($stmt1, "i", $id);
        mysqli_stmt_execute($stmt1);

        $stmt2 = mysqli_prepare($conn, "DELETE FROM suppliers WHERE supplier_id = ?;");
        mysqli_stmt_bind_param($stmt2, "i", $id);
        mysqli_stmt_execute($stmt2);
    }
    mysqli_close($conn);

    $_SESSION['deleted'] = "Supplier Deleted Successfully";
    header("location: ../supplier_list.php");
    exit();
} else {
    header("location: ../supplier_list.php");
    exit();
}
