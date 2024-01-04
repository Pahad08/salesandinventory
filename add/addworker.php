<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../index.php");
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
    $username = CleanData($conn, $_POST['username']);
    $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_BCRYPT);
    $role = 2;

    $stmt_exist = mysqli_prepare($conn, "SELECT account_id, username FROM accounts where username = ?");
    mysqli_stmt_bind_param($stmt_exist, "s", $username);
    mysqli_stmt_execute($stmt_exist);
    $result = mysqli_stmt_get_result($stmt_exist);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['exist'] = "Username Already Taken";
        mysqli_close($conn);
        header("location: ../workers_list.php");
        exit();
    } else {

        $stmt = mysqli_prepare($conn, "INSERT INTO accounts(username, `password`, `role`) VALUES(?,?,?);");
        mysqli_stmt_bind_param($stmt, "ssi", $username, $password, $role);
        mysqli_stmt_execute($stmt);

        $account_id = mysqli_insert_id($conn);

        $sql = "INSERT INTO workers(f_name, l_name, contact_number, account_id) VALUES(?,?,?,?);";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $fname, $lname, $number, $account_id);
        mysqli_stmt_execute($stmt);
        $_SESSION['added'] = "Worker Added Successfully";
        mysqli_close($conn);
        header("location: ../workers_list.php");
        exit();
    }
} else {
    header("location: ../workers_list.php");
    exit();
}
