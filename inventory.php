<?php
session_start();
include 'openconn.php';

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
    && !isset($_SESSION["supplier"]) && !isset($_SESSION["supplier_username"])
) {
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
    <title>Inventory</title>
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
            <h3><?php
                if (isset($_SESSION["admin_username"])) {
                    echo strtoupper($_SESSION["admin_username"]);
                } elseif (isset($_SESSION["worker_username"])) {
                    echo strtoupper($_SESSION["worker_username"]);
                } else {
                    echo strtoupper($_SESSION["supplier_username"]);
                }
                ?>
            </h3>
            <a href="logout.php">Logout</a>
        </div>

    </div>


    <div id="nav-body" class="nav">
        <nav id="nav">
            <div id="list-container">

                <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                    <ul class="menu">
                        <p>Data Dashboard</p>
                        <li><a href="admin.php">Dashboard</a></li>
                    </ul>
                <?php } elseif (isset($_SESSION["worker"]) && isset($_SESSION["worker_username"])) { ?>
                    <ul class="menu">
                        <p>Account Details</p>
                        <li><a href="worker.php">Account</a></li>
                    </ul>
                <?php } else if (isset($_SESSION["supplier"]) && isset($_SESSION["supplier_username"])) { ?>
                    <ul class="menu">
                        <p>Account Details</p>
                        <li><a href="worker.php">Account</a></li>
                    </ul>
                <?php } ?>

                <ul class="menu">
                    <p>Products</p>
                    <?php if (
                        isset($_SESSION["admin"]) || isset($_SESSION["admin_username"])
                        || isset($_SESSION["worker"]) || isset($_SESSION["worker_username"])
                        || isset($_SESSION["supplier"]) || isset($_SESSION["supplier_username"])
                    ) { ?>
                        <li><a href="inventory.php">Inventory</a></li>
                    <?php } ?>

                    <?php if (
                        isset($_SESSION["admin"]) || isset($_SESSION["admin_username"])
                        || isset($_SESSION["worker"]) || isset($_SESSION["worker_username"])
                    ) { ?>
                        <li><a href="products.php">Product List</a></li>
                        <li><a href="sales.php">Sales</a></li>
                    <?php } ?>

                    <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                        <li><a href="expense.php">Expenses</a></li>
                    <?php } ?>
                </ul>


                <?php if (
                    isset($_SESSION["supplier"]) || isset($_SESSION["supplier_username"])
                    || isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])
                ) { ?>
                    <ul class="menu">
                        <p>Suppliers/Workers</p>
                        <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                            <li><a href="supplier_list.php">List of Suppliers</a></li>
                            <li><a href="workers_list.php">List of Workers</a></li>
                        <?php } ?>

                        <li><a href="schedules.php">Schedule of Deliveries</a></li>
                    </ul>

                <?php } ?>

                <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                    <ul class="menu">
                        <p>Users</p>
                        <li><a href="users.php">Users List</a></li>
                    </ul>
                <?php } ?>

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
                        <button id="searchbtn" onclick="ShowStocks(search.value) ">Search</button>
                    </div>

                </div>

                <table id="table">
                    <tr id="head">
                        <th></th>
                        <th>Product Name</th>
                        <th>Quantities</th>
                        <th>Stock In</th>
                        <th>Stock Out</th>
                    </tr>
                    <?php while ($row) { ?>
                        <tr>
                            <td></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['quantities']; ?></td>
                            <td><?php echo $row['stock_in']; ?></td>
                            <td><?php echo $row['stock_out']; ?></td>
                        </tr>

                    <?php $row = mysqli_fetch_array($result);
                    } ?>

                </table>

                <div class="page">

                    <p><?php echo "Page " . "<b>$page_number </b>" . " of " . "<b>$total_pages</b>" ?></p>

                    <ul class="page-list">
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

    </div>

</body>

<script src="javascript/admin.js"></script>
<script>
    function ShowStocks(str) {
        const stocks = new XMLHttpRequest();
        stocks.onload = function() {
            document.getElementById("table").innerHTML =
                this.responseText;
        }
        stocks.open("GET", "search/inventory.php?name=" + str);
        stocks.send();
    }
    document.getElementById("search").addEventListener("input", () => {
        if (document.getElementById("search").value == "") {
            const stocks = new XMLHttpRequest();
            stocks.onload = function() {
                document.getElementById("table").innerHTML =
                    this.responseText;
            }
            stocks.open("GET", "search/inventory.php?");
            stocks.send();
        }
    })
</script>

</html>