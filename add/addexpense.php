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

    $description = CleanData($conn, $_POST['description']);
    $amount = $_POST['amount'];

    $sql = "INSERT INTO expenses(`description`, amount, expense_date) VALUES(?,?,CURRENT_DATE());";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $description, $amount);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Expense Added Successfully";
    mysqli_close($conn);
    header("location: ../expense.php");
    exit();
} else {
    header("location: ../expense.php");
    exit();
}
