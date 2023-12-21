<?php
session_start();
include '../openconn.php';

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

if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $description = CleanData($conn, $_POST['description']);
    $amount = $_POST['amount'];

    $sql = "UPDATE expenses SET `description`=?, amount = ? where expense_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $description, $amount, $id);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Expense Updated Successfully";
    mysqli_close($conn);
    header("location: ../expense.php");
    exit();
} else {
    header("location: ../expense.php");
    exit();
}
