<?php
session_start();

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

if (isset($_POST['add'])) {
    include '../openconn.php';

    $fname = CleanData($conn, $_POST['fname']);
    $lname = CleanData($conn, $_POST['lname']);
    $number = $_POST['number'];
    $company = CleanData($conn, $_POST['company']);

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
