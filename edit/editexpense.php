<?php
session_start();
include '../openconn.php';

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


if (isset($_POST['edit'])) {

    $id = CleanData($_POST['id']);
    $description = CleanData($_POST['description']);
    $amount = CleanData($_POST['amount']);
    $date = $_POST['date'];

    $sql = "UPDATE expenses SET `description`=?, amount = ?, expense_date = ? where expense_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sisi", $description, $amount, $date, $id);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Expense Updated Successfully";
    mysqli_close($conn);
    header("location: ../expense.php");
    exit();
} else {
    header("location: ../expense.php");
    exit();
}
