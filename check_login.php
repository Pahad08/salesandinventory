<?php

session_start();


function CleanData($conn, $data)
{
    $data = stripslashes($data);
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}


if (isset($_POST["submit"])) {
    include 'openconn.php';

    $username = CleanData($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $sql = "SELECT * from accounts WHERE username = ?";
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
        header("location: index.php");
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

            $statement = mysqli_prepare($conn, "SELECT * FROM workers WHERE account_id = ?");
            mysqli_stmt_bind_param($statement, "i", $row['account_id']);
            mysqli_stmt_execute($statement);
            $result2 = mysqli_stmt_get_result($statement);
            if (mysqli_num_rows($result2) > 0) {
                $_SESSION["worker"] = $row["account_id"];
                $_SESSION["worker_username"] = $row["username"];
                mysqli_close($conn);
                header("location:worker.php");
                exit();
            } else {
                mysqli_close($conn);
                $_SESSION["missing"] = "Account not found!";
                header("location: index.php");
                exit();
            }
        } else {

            $statement = mysqli_prepare($conn, "SELECT * FROM suppliers WHERE account_id = ?");
            mysqli_stmt_bind_param($statement, "i", $row['account_id']);
            mysqli_stmt_execute($statement);
            $result2 = mysqli_stmt_get_result($statement);
            if (mysqli_num_rows($result2) > 0) {
                $_SESSION["supplier"] = $row["account_id"];
                $_SESSION["supplier_username"] = $row["username"];
                mysqli_close($conn);
                header("location:supplier.php");
                exit();
            } else {
                mysqli_close($conn);
                $_SESSION["missing"] = "Account not found!";
                header("location: index.php");
                exit();
            }
        }
    } else {
        mysqli_close($conn);
        $_SESSION["missing"] = "Wrong Password!";
        header("location: index.php");
        exit();
    }
} else {
    mysqli_close($conn);
    header("location: index.php");
    exit();
}
