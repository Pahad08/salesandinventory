<?php
session_start();
include '../openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location:../login.php");
    exit();
}

function CleanData($data)
{
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}


if (isset($_POST['add'])) {

    $fname = CleanData($_POST['fname']);
    $lname = CleanData($_POST['lname']);
    $number = $_POST['number'];

    if (empty($_POST['account-id'])) {
        $account_id = null;
    } else {
        $account_id = $_POST['account-id'];
    }

    $sql = "INSERT INTO workers(f_name, l_name, contact_number, account_id) VALUES(?,?,?,?);";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $fname, $lname, $number, $account_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Worker Added Successfully";
    mysqli_close($conn);
    header("location: ../workers_list.php");
    exit();
} else {
    header("location: ../workers_list.php");
    exit();
}
