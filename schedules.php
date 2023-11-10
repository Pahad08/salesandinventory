<?php

session_start();
include 'openconn.php';

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
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

$sql = "SELECT `transaction`.*, products.name, suppliers.f_name, suppliers.l_name
FROM `transaction`
LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
LEFT JOIN products on `transaction`.`product_id` = products.product_id
LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$stmt_sched = mysqli_prepare($conn, "SELECT `transaction`.*, products.name
FROM `transaction`
LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
LEFT JOIN products on `transaction`.`product_id` = products.product_id
where suppliers.account_id = ?
LIMIT $number_per_page OFFSET $offset;");
mysqli_stmt_bind_param($stmt_sched, "i", $_SESSION["supplier"]);
mysqli_stmt_execute($stmt_sched);
$result_sched = mysqli_stmt_get_result($stmt_sched);
$row_sched = mysqli_fetch_array($result_sched);

$sql2 = "SELECT count(transaction_id) as total from `transaction`;";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$Row = mysqli_fetch_array($result2);

$total_records = $Row['total'];
$total_pages = ceil($total_records / $number_per_page);
$starting_page = max(1, $page_number - 2);
$ending_page = min($total_pages, $starting_page + 4);

$stmt_suppliers = mysqli_prepare($conn, "SELECT f_name, l_name, supplier_id from suppliers;");
mysqli_stmt_execute($stmt_suppliers);
$result_suppliers = mysqli_stmt_get_result($stmt_suppliers);
$row_suppliers = mysqli_fetch_array($result_suppliers);

$stmt_products = mysqli_prepare($conn, "SELECT `name`, product_id from products;");
mysqli_stmt_execute($stmt_products);
$result_products = mysqli_stmt_get_result($stmt_products);
$row_products = mysqli_fetch_array($result_products);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
        <title>Schedules</title>
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
                <h3><?php echo $username = (isset($_SESSION["admin_username"])) ?
                    strtoupper($_SESSION["admin_username"]) :  strtoupper($_SESSION["supplier_username"])  ?>
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

                <div class="form" id="form">

                    <div class="form-container">

                        <div class="header-form">
                            <h2>Add Delivery Schedule</h2>
                            <p id="closebtn">&#10006;</p>
                        </div>

                        <form action="add/addtransaction.php" method="post" id="form-sched" class="schedule_add">

                            <div class="input-body">
                                <label for="delivery-date">Delivery Date</label>
                                <input type="date" id="delivery-date" name="delivery_date">
                                <p class="emptyinput" id="deliveryerr">Delivery Date cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity">
                                <p class="emptyinput" id="quantityerr">Quantity cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="supplier">Supplier</label>
                                <select name="supplier_id" id="supplier">
                                    <option value="">Select Supplier</option>
                                    <?php while ($row_suppliers) { ?>
                                    <option value="<?php echo $row_suppliers['supplier_id']; ?>">
                                        <?php echo $row_suppliers['f_name'] . " " . $row_suppliers['l_name']; ?>
                                    </option>
                                    <?php $row_suppliers = mysqli_fetch_array($result_suppliers);
                                } ?>
                                </select>
                                <p class="emptyinput" id="suppliererr">Supplier cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="product">Products</label>
                                <select name="product_id" id="product">
                                    <option value="">Select Product</option>
                                    <?php while ($row_products) { ?>
                                    <option value="<?php echo $row_products['product_id']; ?>">
                                        <?php echo $row_products['name']; ?>
                                    </option>
                                    <?php $row_products = mysqli_fetch_array($result_products);
                                } ?>
                                </select>
                                <p class="emptyinput" id="producterr">Product cannot be blank</p>
                            </div>

                            <div class="buttons">
                                <button type="submit" id="add" name="add">Add</button>
                                <button id="reset">Reset</button>
                            </div>

                        </form>

                    </div>

                </div>

                <?php if (isset($_SESSION['added'])) { ?>
                <div class="added">
                    <p><?php echo $_SESSION['added']; ?></p>
                </div>
                <?php unset($_SESSION['added']);
            } else if (isset($_SESSION['deleted'])) { ?>
                <div class="deleted">
                    <p><?php echo $_SESSION['deleted']; ?></p>
                </div>
                <?php unset($_SESSION['deleted']);
            } else if (isset($_SESSION['updated'])) { ?>
                <div class="updated">
                    <p><?php echo $_SESSION['updated']; ?></p>
                </div>
                <?php unset($_SESSION['updated']);
            } else if (isset($_SESSION['receive'])) { ?>
                <div class="prodreceive">
                    <p><?php echo $_SESSION['receive']; ?></p>
                </div>
                <?php unset($_SESSION['receive']);
            } ?>

                <div class="data-body">

                    <div class="table-header">

                        <div class="header-info">
                            <h2>Delivery Schedule</h2>
                            <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                            <div class="btns">
                                <button id="schedadd" class="add"><img src="images/add.png" alt="">Add
                                    Delivery Schedule</button>
                                <button id="delete"><img src="images/delete.png">Delete</button>
                                <button id="selectall"><img src="images/selectall.png" alt="">Select All</button>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="search">
                            <input type="text" id="search" placeholder="Search">
                        </div>

                    </div>

                    <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                    <form action="delete/deletesched.php" id="deletesched" method="post" class="form-table">
                        <?php } ?>
                        <table id="table">
                            <tr id="head">
                                <?php if (isset($_SESSION['admin']) && isset($_SESSION['admin_username'])) { ?>
                                <th></th>
                                <th>Supplier Name</th>
                                <?php } ?>
                                <th>Transaction Date</th>
                                <th>Delivery Schedule</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                                <th>Action</th>
                                <?php } ?>
                            </tr>

                            <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                            <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="transaction_id[]"
                                        value="<?php echo $row['transaction_id']; ?>" class="checkbox"></td>
                                <td><?php echo $row['f_name'] . " " . $row['l_name']; ?></td>
                                <td><?php echo $row['transaction_date']; ?></td>
                                <td><?php echo $row['delivery_schedule']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td id="sched-btns"> <button class="edit"
                                        data-transactionid="<?php echo $row['transaction_id']; ?>"
                                        data-fname="<?php echo $row['f_name']; ?>"
                                        data-lname="<?php echo $row['l_name']; ?>"
                                        data-supplierid="<?php echo $row['supplier_id']; ?>"
                                        data-quantity="<?php echo $row['quantity']; ?>"
                                        data-prodid="<?php echo $row['product_id']; ?>"
                                        data-prodname="<?php echo $row['name']; ?>"
                                        data-transaction="<?php echo $row['transaction_date']; ?>"
                                        data-delivery="<?php echo $row['delivery_schedule']; ?>">
                                        <img src="images/edit.png" alt="">Edit</button>
                                    <button id="receive" class="receive"
                                        data-id="<?php echo $row['transaction_id']; ?>"><img src="images/received.png"
                                            alt="">Receive</button>
                                </td>
                            </tr>
                            <?php $row = mysqli_fetch_array($result);
                            } ?>
                            <?php } else { ?>
                            <?php while ($row_sched) { ?>
                            <tr>
                                <td><?php echo $row_sched['transaction_date']; ?></td>
                                <td><?php echo $row_sched['delivery_schedule']; ?></td>
                                <td><?php echo $row_sched['name']; ?></td>
                                <td><?php echo $row_sched['quantity']; ?></td>
                            </tr>
                            <?php $row_sched = mysqli_fetch_array($result_sched);
                            } ?>
                            <?php } ?>
                        </table>
                        <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                    </form>
                    <?php } ?>

                    <div class="receive-body" id="receive-body">
                        <div class="alert-container">
                            <img src="images/warning.png">
                            <div class="text-warning">
                                <p>Are you sure you want to receive the item?<br>
                                    (Schedule will also be deleted)
                            </div>
                            <div class="buttons-alert">
                                <a href="" id="del-sched">Receive</a>
                                <button id="close-receive">Cancel</button>
                            </div>
                        </div>
                    </div>

                    </tr>

                    <div class="alert-body" id="alert-body">
                        <div class="alert-container">
                            <img src="images/warning.png">
                            <div class="text-warning">
                                <p>Are you sure you want to delete all selected items?
                            </div>
                            <div class="buttons-alert">
                                <button id="del">Delete</button>
                                <button id="close-deletion">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <div class="page">

                        <p><?php echo "Page " . "<b>$page_number</b>" . " of " . "<b>$total_pages</b>" ?>

                        <ul class="page-list">
                            <li><a <?php if ($page_number != 1) {
                                    echo "href=schedules.php?page_number=" . $previouspage;
                                } ?>>&laquo;</a></li>

                            <?php for ($i = $starting_page; $i <= $ending_page; $i++) { ?>
                            <li><a href="<?php echo "schedules.php?page_number=" . $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php } ?>


                            <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                    echo "href=schedules.php?page_number=" . $nextpage;
                                } ?>>&raquo;</a></li>
                        </ul>

                    </div>

                </div>

                <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                <div class="modal-transaction">
                    <?php include 'modal/transaction_modal.php'; ?>
                </div>
                <?php } ?>

            </div>


        </div>
    </body>

    <script src="javascript/navigation.js"></script>
    <script src="javascript/schedule.js"> </script>

</html>