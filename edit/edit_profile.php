<?php
session_start();

if (
    !isset($_SESSION["supplier"]) && !isset($_SESSION["supplier_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
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

    $id = CleanData($_POST['id']);
    $fname = CleanData($_POST['fname']);
    $lname = CleanData($_POST['lname']);
    $number = CleanData($_POST['number']);

    $stmt_role = mysqli_prepare($conn, "SELECT accounts.role from accounts
    LEFT join workers on accounts.account_id = workers.account_id
    LEFT join suppliers on accounts.account_id = suppliers.account_id
    where workers.worker_id = ? or suppliers.supplier_id = ?");
    mysqli_stmt_bind_param($stmt_role, "ii", $id, $id);
    mysqli_stmt_execute($stmt_role);
    $result = mysqli_stmt_get_result($stmt_role);
    $row = mysqli_fetch_array($result);

    if ($row['role'] == 2) {
        $sql = "UPDATE workers SET f_name=?, l_name= ?, contact_number = ? where worker_id = ?;";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $fname, $lname, $number, $id);
        mysqli_stmt_execute($stmt);
        $_SESSION['updated'] = "Account Updated Successfully";
        mysqli_close($conn);
        header("location: ../worker.php");
        exit();
    } else {
        $company = CleanData($_POST['company']);

        $sql = "UPDATE suppliers SET f_name=?, l_name= ?, contact_number = ?, company_name = ? 
        where supplier_id = ?;";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $fname, $lname, $number, $company, $id);
        mysqli_stmt_execute($stmt);
        $_SESSION['updated'] = "Account Updated Successfully";
        mysqli_close($conn);
        header("location: ../worker.php");
        exit();
    }
} else {
    header("location: ../users.php");
    exit();
}
