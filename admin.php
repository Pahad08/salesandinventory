<?php

session_start();
include 'openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
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
            <div class="head">
                <h1>Dashboard</h1>
            </div>

            <div class="boxes">

                <div class="box" id="box1">
                    <img src="images/sales.png" alt="">
                    <div class="info">
                        <p class="sale-text"><?php
                                            $sql = "SELECT sum(sales.quantity * products.price) as sale, sales.sale_date
                                            from sales
                                            inner JOIN products on sales.product_id = products.product_id 
                                            where DAY(sales.sale_date) = DAY(CURRENT_DATE);";
                                            $stmt = mysqli_prepare($conn, $sql);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            $row = mysqli_fetch_array($result);
                                            echo "₱" . $row['sale'];
                                            ?></p>
                        <h3>Sales</h3>
                    </div>
                </div>

                <div class="box" id="box2">
                    <img src="images/supplier.png" alt="">
                    <div class="info">
                        <p class="sale-text"><?php
                                            $date = date("Y-m-d");
                                            $sql = "SELECT count(supplier_id) as total FROM suppliers;";
                                            $stmt = mysqli_prepare($conn, $sql);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            $row = mysqli_fetch_array($result);
                                            echo  $row['total'];
                                            ?></p>
                        <h3>Suppliers</h3>
                    </div>
                </div>

                <div class="box" id="box3">

                    <img src="images/products.png" alt="">
                    <div class="info">
                        <p class="sale-text"><?php
                                            $sql = "SELECT count(product_id) as total FROM products;";
                                            $stmt = mysqli_prepare($conn, $sql);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            $row = mysqli_fetch_array($result);
                                            echo $row['total'];
                                            ?></p>
                        <h3>Products</h3>
                    </div>
                </div>

                <div class="box" id="box4">
                    <img src="images/inventory.png" alt="">
                    <div class="info">
                        <p class="sale-text"><?php
                                            $sql = "SELECT count(stock_id) as total FROM stocks;";
                                            $stmt = mysqli_prepare($conn, $sql);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            $row = mysqli_fetch_array($result);
                                            echo $row['total'];
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

            </div>

        </div>

        </div>

    </body>

    <script src="javascript/admin.js"></script>

</html>