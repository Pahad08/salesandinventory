<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: login.php");
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

    $description = CleanData($_POST['description']);
    $amount = CleanData($_POST['amount']);
    $date = date("Y-m-d", strtotime($_POST['expense-date']));

    $sql = "INSERT INTO expenses(`description`, amount, expense_date) VALUES(?,?,?);";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sis", $description, $amount, $date);
    mysqli_stmt_execute($stmt);
    $_SESSION['added'] = "Expense Added Successfully";
    mysqli_close($conn);
    header("location: ../expense.php");
    exit();
} else {
    header("location: ../expense.php");
    exit();
}
