<?php
session_start();
include 'openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: login.php");
    exit();
}

if (isset($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
} else {
    $page_number = 1;
}

$number_per_page = 5;
$offset = ($page_number - 1) * $number_per_page;
$nextpage = $page_number + 1;
$previouspage = $page_number - 1;

$sql = "SELECT products.name, stocks.stock_id, stocks.quantities, stocks.stock_in, stocks.stock_out
from stocks
join products on stocks.product_id = products.product_id LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(stock_id) as total from stocks;";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$Row = mysqli_fetch_array($result2);

$total_records = $Row['total'];
$total_pages = ceil($total_records / $number_per_page);

$sql3 = "SELECT product_id, `name` from products";
$stmt3 = mysqli_prepare($conn, $sql3);
mysqli_stmt_execute($stmt3);
$result3 = mysqli_stmt_get_result($stmt3);
$row_product = mysqli_fetch_array($result3);
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <title>Admin</title>
</head>

<body>

    <div class="header">

        <div class="left">

            <div id="menu-icon">
                <div></div>
                <div></div>
                <div></div>
            </div>

            <img src="images/logo.png" alt="logo">
            <h2> Badong Lechon Manok</h2>
        </div>

        <div class="right">
            <h3><?php echo strtoupper($_SESSION["admin_username"]); ?> </h3>
            <a href="logout.php">Logout</a>
        </div>

    </div>


    <div id="nav-body" class="nav">
        <nav id="nav">
            <div id="list-container">

                <ul class="menu">
                    <p>Data Dashboard</p>
                    <li><a href="admin.php">Dashboard</a></li>
                </ul>

                <ul class="menu">
                    <p>Products</p>
                    <li><a href="inventory.php">Inventory</a></li>
                    <li><a href="products.php">Product List</a></li>
                    <li><a href="sales.php">Sales</a></li>
                    <li><a href="expense.php">Expenses</a></li>
                </ul>

                <ul class="menu">
                    <p>Suppliers/Workers</p>
                    <li><a href="supplier_list.php">List of Suppliers</a></li>
                    <li><a href="workers_list.php">List of Workers</a></li>
                    <li><a href="schedules.php">Schedule of Deliveries</a></li>
                </ul>

                <ul class="menu">
                    <p>Users</p>
                    <li><a href="users.php">Users List</a></li>
                </ul>
            </div>

            <div class="nav-footer">
                <p>User Role: Admin</p>
            </div>
        </nav>
    </div>


    <div class="body">

        <div class="body-content">

            <div class="stock-list">

                <div class="table-header">

                    <div class="header-info">
                        <h2>Inventory</h2>
                    </div>

                    <div class="search">
                        <input type="text" id="search" placeholder="Search">
                    </div>

                </div>

                <table id="table">
                    <tr id="head">
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Quantities</th>
                        <th>Stock In</th>
                        <th>Stock Out</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row) { ?>
                        <tr>
                            <td><?php echo $row['stock_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['quantities']; ?></td>
                            <td><?php echo $row['stock_in']; ?></td>
                            <td><?php echo $row['stock_out']; ?></td>
                            <td id="action"> <button class="edit">Edit</button>
                                <button class="delete">Del</button>
                            </td>
                        </tr>

                    <?php $row = mysqli_fetch_array($result);
                    } ?>

                </table>

                <ul class="page">
                    <li><a <?php if ($page_number != 1) {
                                echo "href=inventory.php?page_number=" . $previouspage;
                            } ?>>&laquo;</a></li>

                    <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                        <li><a href="<?php echo "inventory.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
                        </li>
                    <?php } ?>


                    <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                echo "href=inventory.php?page_number=" . $nextpage;
                            } ?>>&raquo;</a></li>
                </ul>

            </div>
        </div>

    </div>

</body>

<script src="javascript/admin.js"></script>

</html>