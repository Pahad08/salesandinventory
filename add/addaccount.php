<?php
session_start();


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
    include '../openconn.php';

    $username = CleanData($_POST['username']);
    $password = password_hash(CleanData($_POST['password']), PASSWORD_BCRYPT);
    $role = CleanData($_POST['role']);

    $stmt_exist = mysqli_prepare($conn, "SELECT account_id, username FROM accounts where username = ?");
    mysqli_stmt_bind_param($stmt_exist, "s", $username);
    mysqli_stmt_execute($stmt_exist);
    $result = mysqli_stmt_get_result($stmt_exist);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['exist'] = "Username Already Taken";
        mysqli_close($conn);
        header("location: ../users.php");
        exit();
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO accounts(username, `password`, role) VALUES(?,?,?);");
        mysqli_stmt_bind_param($stmt, "ssi", $username, $password, $role);
        mysqli_stmt_execute($stmt);
        $_SESSION['added'] = "Username Added Successfully";
        mysqli_close($conn);
        header("location: ../users.php");
        exit();
    }
} else {
    header("location: ../users.php");
    exit();
}