<?php

session_start();

if (isset($_POST["submit"])) {
    include 'openconn.php';

    $username = trim(stripslashes($_POST["username"]));
    $password = $_POST["password"];

    $sql = "SELECT accounts.account_id, accounts.username, accounts.password, accounts.role, accounts.username
    FROM accounts
    LEFT JOIN workers on accounts.account_id = workers.account_id
    LEFT JOIN suppliers on accounts.account_id = suppliers.account_id
    WHERE accounts.username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $userpassword = $row['password'];
    } else {
        $_SESSION["missing"] = "Account not found!";
        mysqli_close($conn);
        header("location: login.php");
        exit();
    }

    if (password_verify($password, $userpassword)) {
        if ($row["role"] == "1") {
            $_SESSION["admin"] = $row["account_id"];
            $_SESSION["admin_username"] = $row["username"];
            mysqli_close($conn);
            header("location:admin.php");
            exit();
        } elseif ($row["role"] == "2") {
            $_SESSION["worker"] = $row["account_id"];
            $_SESSION["worker_username"] = $row["username"];
            mysqli_close($conn);
            header("location:admin.php");
            exit();
        } else {
            $_SESSION["supplier"] = $row["account_id"];
            $_SESSION["supplier_username"] = $row["username"];
            mysqli_close($conn);
            header("location:supplier.php");
            exit();
        }
    } else {
        mysqli_close($conn);
        $_SESSION["missing"] = "Account not found!";
        header("location: login.php");
        exit();
    }
} else {
    mysqli_close($conn);
    header("location: login.php");
    exit();
}
