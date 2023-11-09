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

function UpdateSales($conn, $prodid, $quantity, $saleid)
{
    $sql = "UPDATE sales SET product_id = ?, quantity = ? where sale_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $prodid, $quantity, $saleid);
    mysqli_stmt_execute($stmt);
    $_SESSION['updated'] = "Sale Updated Successfully";
    mysqli_close($conn);
    header("location: ../sales.php");
    exit();
}

function CheckQuantity($conn, $curr_prodid)
{
    $stmt = mysqli_prepare($conn, "SELECT quantities from stocks where product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $curr_prodid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    return $row['quantities'];
}

if (isset($_POST['edit'])) {
    include '../openconn.php';
    $saleid = CleanData($_POST['saleid']);
    $prodid = CleanData($_POST['prodid']);
    $quantity = CleanData($_POST['quantity']);
    $curr_quantity = CleanData($_POST['curr_quantity']);
    $curr_prodid = CleanData($_POST['currprod']);

    if ($prodid == $curr_prodid) {

        if (CheckQuantity($conn, $curr_prodid) === 0) {
            $_SESSION['emptystocks'] = "Empty stock";
            mysqli_close($conn);
            header("location: ../sales.php");
            exit();
        } elseif (CheckQuantity($conn, $curr_prodid) < $quantity) {
            $_SESSION['lessquantity'] = "Stock is lower than the entered quantity";
            mysqli_close($conn);
            header("location: ../sales.php");
            exit();
        } else {
            $stm = mysqli_prepare($conn, "UPDATE stocks SET quantities =(quantities + ?)-?, stock_out = (stock_out - ?)+?  where product_id = ?");
            mysqli_stmt_bind_param($stm, "iiiii", $curr_quantity, $quantity, $curr_quantity, $quantity, $curr_prodid);
            mysqli_stmt_execute($stm);

            UpdateSales(
                $conn,
                $prodid,
                $quantity,
                $saleid
            );
        }
    } else {

        if (CheckQuantity($conn, $prodid) === 0) {
            $_SESSION['emptystocks'] = "Empty stock";
            mysqli_close($conn);
            header("location: ../sales.php");
            exit();
        } elseif (CheckQuantity($conn, $prodid) < $quantity) {
            $_SESSION['lessquantity'] = "Stock is lower than the entered quantity";
            mysqli_close($conn);
            header("location: ../sales.php");
            exit();
        } else {

            $stm = mysqli_prepare($conn, "UPDATE stocks SET quantities = quantities + ?, stock_out = stock_out - ?  where product_id = ?");
            mysqli_stmt_bind_param($stm, "iii", $curr_quantity, $curr_quantity, $curr_prodid);
            mysqli_stmt_execute($stm);

            $statement = mysqli_prepare($conn, "UPDATE stocks SET quantities = quantities - ?, stock_out = stock_out + ?  where product_id = ?");
            mysqli_stmt_bind_param($statement, "iii", $quantity, $quantity, $prodid);
            mysqli_stmt_execute($statement);

            UpdateSales(
                $conn,
                $prodid,
                $quantity,
                $saleid
            );
        }
    }
} else {
    header("location: ../sales.php");
    exit();
}
