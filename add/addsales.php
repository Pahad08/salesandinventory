<?php
session_start();

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
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

if (isset($_POST['add'])) {
    include '../openconn.php';
    $prod_id = CleanData($_POST['prodid']);
    $date =  date("Y-m-d", strtotime($_POST['date']));
    $quantity = CleanData($_POST['quantity']);

    $statement = mysqli_prepare($conn, "SELECT * from stocks where product_id = ?;");
    mysqli_stmt_bind_param($statement, "i", $prod_id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_array($result);

    if (mysqli_num_rows($result) > 0) {

        if ($row['quantities'] < $quantity) {
            $_SESSION['lessquantity'] = "Quantity given is greater than the stock available";
            mysqli_close($conn);
            header("location: ../sales.php");
            exit();
        } else {
            $stm = mysqli_prepare($conn, "UPDATE stocks SET quantities = quantities - ?, stock_out = stock_out + ?  where product_id = ?");
            mysqli_stmt_bind_param($stm, "iii", $quantity, $quantity, $prod_id);
            mysqli_stmt_execute($stm);
        }

        $sql = "INSERT INTO sales(`sale_date`, product_id, quantity) VALUES(CURRENT_DATE(),?,?);";
        $stmt2 = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt2, "ii", $prod_id, $quantity);
        mysqli_stmt_execute($stmt2);
        $_SESSION['added'] = "Sale Added Successfully";
        mysqli_close($conn);
        header("location: ../sales.php");
        exit();
    } else {
        $_SESSION['emptystocks'] = "No Stocks Available";
        mysqli_close($conn);
        header("location: ../sales.php");
        exit();
    }
} else {
    header("location: ../sales.php");
    exit();
}
