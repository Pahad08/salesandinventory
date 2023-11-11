<?php
session_start();
include '../openconn.php';

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

function CleanData($conn, $data)
{
    $data = stripslashes($data);
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

if (isset($_POST['edit'])) {

    $id = $_POST['supplier_id'];
    $fname = CleanData($conn, $_POST['fname']);
    $lname = CleanData($conn, $_POST['lname']);
    $number = $_POST['number'];
    $company = CleanData($conn, $_POST['company']);

    if (empty($_POST['account-id'])) {
        $account_id = null;
    } else {
        $account_id = $_POST['account-id'];
    }

    $sql = "UPDATE suppliers SET f_name=?, l_name=?, contact_number = ?, company_name=?, account_id = ? where supplier_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssii", $fname, $lname, $number, $company, $account_id, $id);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Supplier Updated Successfully";
    mysqli_close($conn);
    header("location: ../supplier_list.php");
    exit();
} else {
    header("location: ../supplier_list.php");
    exit();
}
