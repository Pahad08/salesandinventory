<?php
include 'openconn.php';
session_start();

if (isset($_POST["submit"])) {
    $username = trim(stripslashes($_POST["username"]));
    $password = $_POST["password"];

    $sql = "SELECT accounts.account_id, accounts.username, accounts.password, accounts.role, accounts.username
    FROM accounts
    LEFT JOIN admins on accounts.account_id = admins.account_id
    LEFT JOIN workers on accounts.account_id = workers.account_id
    LEFT JOIN suppliers on accounts.account_id = suppliers.account_id
    WHERE accounts.username = ? and accounts.password = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    if (mysqli_num_rows($result) > 0) {
        if ($row["role"] == "1") {
            $_SESSION["admin"] = $row["account_id"];
            $_SESSION["admin_username"] = $row["username"];
            setcookie('admin_id', $row["account_id"], time() + (365 * 24 * 3600), '/');
            setcookie('admin_username', $row["username"], time() + (365 * 24 * 3600), '/');
            header("location:admin.php");
            exit();
        } elseif ($row["role"] == "2") {
            $_SESSION["worker"] = $row["account_id"];
            $_SESSION["worker_username"] = $row["username"];
            header("location:admin.php");
            exit();
        } else {
            $_SESSION["supplier"] = $row["account_id"];
            $_SESSION["supplier_username"] = $row["username"];
            header("location:supplier.php");
            exit();
        }
    } else {
        $_SESSION["missing"] = "Account not found!";
        header("location: login.php");
        exit();
    }
} else {
    header("location: login.php");
    exit();
}