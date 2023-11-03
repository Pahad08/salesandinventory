<?php
session_start();

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: login.php");
    exit();
}

function CleanData($data)
{
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}

if (isset($_POST['edit'])) {
    include '../openconn.php';

    $id = CleanData($_POST['id']);
    $username = CleanData($_POST['username']);
    $password = $_POST['password'];
    if (!empty($_POST['role'])) {
        $role = CleanData($_POST['role']);
    } else {
        $role = "";
    }


    $stmt = mysqli_prepare($conn, "SELECT username, `password`  FROM accounts where account_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    $stmt2 = mysqli_prepare($conn, "SELECT COUNT(account_id) as total FROM accounts where username = ?");
    mysqli_stmt_bind_param($stmt2, "s", $username);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $row2 = mysqli_fetch_array($result2);

    if ($row['password'] == $password) {
        $new_password = $row['password'];
    } else {
        $new_password = password_hash($password, PASSWORD_BCRYPT);
    }

    if (empty($role)) {

        if ($row['username'] != $username && $row2['total'] != 0) {
            $_SESSION['exist'] = "Username Already Exist";
            mysqli_close($conn);
            header("location: ../users.php");
            exit();
        } else {
            $sql = "UPDATE accounts SET username=?, `password`= ? where account_id = ?;";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $username, $new_password, $id);
            mysqli_stmt_execute($stmt);
            $_SESSION['updated'] = "Account Updated Successfully";
            mysqli_close($conn);
            header("location: ../users.php");
            exit();
        }
    }

    if ($row['username'] == $username || $row2['total'] == 0) {
        $sql = "UPDATE accounts SET username=?, `password`= ?, `role` = ? where account_id = ?;";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $username, $new_password, $role, $id);
        mysqli_stmt_execute($stmt);
        $_SESSION['updated'] = "Account Updated Successfully";
        mysqli_close($conn);
        header("location: ../users.php");
        exit();
    } else {
        $_SESSION['exist'] = "Username Already Exist";
        mysqli_close($conn);
        header("location: ../users.php");
        exit();
    }
} else {
    header("location: ../users.php");
    exit();
}