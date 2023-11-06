<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

if (!empty($_POST['expense_id'])) {
    include '../openconn.php';
    $expense_ids = $_POST['expense_id'];

    foreach ($expense_ids as $id) {
        $stmt = mysqli_prepare($conn, "DELETE FROM expenses WHERE expense_id = ?;");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    mysqli_close($conn);

    $_SESSION['deleted'] = "Expense Deleted Successfully";
    header("location: ../expense.php");
    exit();
} else {
    header("location: ../expense.php");
    exit();
}