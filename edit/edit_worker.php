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

    $id = $_POST['worker_id'];
    $fname = CleanData($conn, $_POST['fname']);
    $lname = CleanData($conn, $_POST['lname']);
    $number = $_POST['number'];

    if (empty($_POST['account-id'])) {
        $account_id = null;
    } else {
        $account_id = $_POST['account-id'];
    }

    $sql = "UPDATE workers SET f_name=?, l_name=?, contact_number = ?, account_id = ? where worker_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssii", $fname, $lname, $number, $account_id, $id);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Worker Updated Successfully";
    mysqli_close($conn);
    header("location: ../workers_list.php");
    exit();
} else {
    header("location: ../workers_list.php");
    exit();
}
