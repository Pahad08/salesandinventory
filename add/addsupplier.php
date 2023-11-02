<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: login.php");
    exit();
}

function CleanData($data)
{
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}


if (isset($_POST['add'])) {
    include '../openconn.php';

    $fname = CleanData($_POST['fname']);
    $lname = CleanData($_POST['lname']);
    $number = $_POST['number'];
    $company = CleanData($_POST['company']);

    if (empty($_POST['account-id'])) {
        $account_id = null;
    } else {
        $account_id = $_POST['account-id'];
    }

    $sql = "INSERT INTO suppliers(f_name, l_name, contact_number, company_name, account_id) VALUES(?,?,?,?,?);";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $fname, $lname, $number, $company, $account_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Supplier Added Successfully";
    mysqli_close($conn);
    header("location: ../supplier_list.php");
    exit();
} else {
    header("location: ../supplier_list.php");
    exit();
}
