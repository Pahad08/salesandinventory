<?php

session_start();
include 'openconn.php';

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: login.php");
    exit();
}

function GetData($conn, $sql)
{
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return $row[0];
}

$date = date("Y-m-d");
$total_sales = GetData($conn, "SELECT sum(sales.quantity * products.price) as sale from sales
inner JOIN products on sales.product_id = products.product_id
where DAY(sales.sale_date) = DAY(CURRENT_DATE);");
$total_suppliers = GetData($conn, "SELECT count(supplier_id) as total FROM suppliers;");
$total_products = GetData($conn, "SELECT count(product_id) as total FROM products;");
$total_stocks = GetData($conn, "SELECT count(stock_id) as total FROM stocks;");
$total_expenses = GetData($conn, "SELECT SUM(amount) as total FROM expenses
where DAY(expense_date) = DAY(CURRENT_DATE);");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="shortcut icon" href="images/logo.jpg" type="image/x-icon">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
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

            <img src="images/logo.jpg" alt="logo">
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

        </nav>
    </div>


    <div class="body">
        <div class="head">
            <h1>Dashboard</h1>
            <button id="generate-btn"><img src="images/report.png">Generate Reports</button>
        </div>

        <div class="boxes">

            <div class="box" id="box1">
                <img src="images/sales.png" alt="">
                <div class="info">
                    <p class="sale-text"><?php echo "₱" . $total_sales; ?></p>
                    <h3>Daily Sales</h3>
                </div>
            </div>

            <div class="box" id="box2">
                <img src="images/supplier.png" alt="">
                <div class="info">
                    <p class="sale-text"><?php echo $total_suppliers; ?></p>
                    <h3>Suppliers</h3>
                </div>
            </div>

            <div class="box" id="box3">

                <img src="images/products.png" alt="">
                <div class="info">
                    <p class="sale-text"><?php echo $total_products; ?></p>
                    <h3>Products</h3>
                </div>
            </div>

            <div class="box" id="box4">
                <img src="images/inventory.png" alt="">
                <div class="info">
                    <p class="sale-text"><?php echo $total_stocks;
                                            ?></p>
                    <h3>Stocks</h3>
                </div>
            </div>
        </div>

        <div class="dashboard">

            <div class="sale-body">
                <?php include 'graphs/sales_graph.php'; ?>
            </div>

            <div class="inventory-body">
                <?php include 'graphs/inventory_graph.php'; ?>
            </div>

            <div class="profit-body">
                <?php include 'graphs/profit_graph.php'; ?>
            </div>

            <div class="sale-product">
                <?php include 'graphs/sale_perproduct.php'; ?>
            </div>

            <div class="sale-permonth">
                <?php include 'graphs/product_permonth.php'; ?>
            </div>

            <div class="expense-body">
                <?php include 'graphs/expenses_graph.php'; ?>
            </div>

        </div>

        <div class="print-container">

            <div class="report-body">

                <div class="print-button">
                    <button id="printbtn"><img src="images/printing.png" alt="">Print</button>
                    <button id="cancelbtn"><img src="images/cancel.png" alt="">Cancel</button>
                </div>

                <div class="print-body">

                    <div class="upper">
                        <img src="images/logo.jpg" alt="">

                        <div class="business-info">
                            <p>Badong Lechon Manok</p>
                            <p>Mr. and Mrs. Lamanero</p>
                            <p>Poblacion, Tacurong City</p>
                            <p class="date">Date: <?php echo date("M-d-Y"); ?></p>
                        </div>
                    </div>


                    <div class="report-table">
                        <table>
                            <tr>
                                <th>Report</th>
                                <th>Info</th>
                            </tr>

                            <tr>
                                <td>Total Sales</td>
                                <td> <?php echo "₱" . $total_sales;
                                        ?></td>
                            </tr>

                            <tr>
                                <td>Remaining Stocks</td>
                                <td><?php echo $total_stocks . " Stocks";
                                    ?></td>
                            </tr>

                            <tr>
                                <td>Total expenses</td>
                                <td><?php echo "₱" . $total_expenses;
                                    ?></td>
                            </tr>

                            <tr>
                                <td>Most Sale Product</td>
                                <td><?php $sql = "SELECT products.name, 
                                MAX( products.price * sales.quantity) as total
                                from sales
                                inner JOIN products on sales.product_id = products.product_id
                                where DAY(sales.sale_date) = DAY(CURRENT_DATE);";
                                    $stmt = mysqli_prepare($conn, $sql);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    $row = mysqli_fetch_array($result);
                                    mysqli_close($conn);
                                    echo $row['name'] . "-" . "₱" . $row['total']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>


</body>

<script src="javascript/navigation.js"></script>
<script>
    document.getElementById("generate-btn").addEventListener("click", () => {
        document.querySelector(".print-container").style.display = "block";
    })

    document.getElementById("cancelbtn").addEventListener("click", () => {
        document.querySelector(".print-container").style.display = "none";
    })

    document.getElementById("printbtn").addEventListener("click", () => {
        window.print();
    })
</script>

</html>