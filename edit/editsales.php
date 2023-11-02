<?php
session_start();

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: ../login.php");
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
    $saleid = CleanData($_POST['saleid']);
    $prodid = CleanData($_POST['prodid']);
    $date = date("Y-m-d", strtotime($_POST['date']));
    $quantity = CleanData($_POST['quantity']);
    $curr_quantity = CleanData($_POST['curr_quantity']);

    $stm = mysqli_prepare($conn, "UPDATE stocks SET quantities =(quantities + ?)-?, stock_out = (stock_out - ?)+?  where product_id = ?");
    mysqli_stmt_bind_param($stm, "iiiii", $curr_quantity, $quantity, $curr_quantity, $quantity, $prodid);
    mysqli_stmt_execute($stm);

    $sql = "UPDATE sales SET sale_date = ?, product_id = ?, quantity = ? where sale_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siii", $date, $prodid, $quantity, $saleid);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Sale Updated Successfully";
    mysqli_close($conn);
    header("location: ../sales.php");
    exit();
} else {
    header("location: ../sales.php");
    exit();
}
