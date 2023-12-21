<?php
session_start();

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../index.php");
    exit();
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

function TotalQuantity($conn, $curr_prodid)
{
    $stmt = mysqli_prepare($conn, "SELECT quantities + stock_out as total_quantity FROM stocks where product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $curr_prodid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    return $row['total_quantity'];
}

function IsAvailable($conn, $curr_prodid)
{
    $stmt = mysqli_prepare($conn, "SELECT stock_id FROM stocks where product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $curr_prodid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return (mysqli_num_rows($result) == 0) ? true : false;
}

function SalesQuantity($conn, $curr_prodid)
{
    $stmt = mysqli_prepare($conn, "SELECT sum(quantity) as total_quantity FROM sales where product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $curr_prodid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    return $row['total_quantity'];
}

if (isset($_POST['edit'])) {
    include '../openconn.php';
    $saleid = $_POST['saleid'];
    $prodid = $_POST['prodid'];
    $quantity = $_POST['quantity'];
    $curr_quantity = $_POST['curr_quantity'];
    $curr_prodid = $_POST['currprod'];

    if ($prodid == $curr_prodid) {

        if (IsAvailable($conn, $curr_prodid)) {

            $_SESSION['emptystocks'] = "Empty stock";
            mysqli_close($conn);
            header("location: ../sales.php");
            exit();
        } elseif (($quantity) > TotalQuantity($conn, $curr_prodid) ||
            (SalesQuantity($conn, $curr_prodid) - $curr_quantity) + ($quantity) > TotalQuantity($conn, $curr_prodid)
        ) {

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

        if (IsAvailable($conn, $prodid)) {
            $_SESSION['emptystocks'] = "Empty stock";
            mysqli_close($conn);
            header("location: ../sales.php");
            exit();
        } elseif (($quantity) > TotalQuantity($conn, $prodid) ||
            (SalesQuantity($conn, $prodid)) + ($quantity) > TotalQuantity($conn, $prodid)
        ) {
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
