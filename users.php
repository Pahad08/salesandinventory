<?php

session_start();
include 'openconn.php';

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
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

$sql = "SELECT * from accounts LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(account_id) as total from accounts;";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$Row = mysqli_fetch_array($result2);
mysqli_close($conn);

$total_records = $Row['total'];
$total_pages = ceil($total_records / $number_per_page);
$starting_page = max(1, $page_number - 2);
$ending_page = min($total_pages, $starting_page + 4);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="shortcut icon" href="images/logo.jpg" type="image/x-icon">
        <title>Accounts</title>
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

            <div class="body-content">

                <div class="form" id="form">

                    <div class="form-container">

                        <div class="header-form">
                            <h2>Add Account</h2>
                            <p id="closebtn">&#10006;</p>
                        </div>

                        <form action="add/addaccount.php" method="post" id="form-body">

                            <div class="input-body">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username">
                                <p class="emptyinput" id="usernameerr">Username cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password">
                                <p class="emptyinput" id="passworderr">Password cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="role">Role</label>
                                <select name="role" id="role">
                                    <option value="">Select Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Worker</option>
                                    <option value="3">Supplier</option>
                                </select>
                                <p class="emptyinput" id="roleerr">Role cannot be blank</p>
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
            } else if (isset($_SESSION['exist'])) { ?>
                <div class="exist">
                    <p><?php echo $_SESSION['exist']; ?></p>
                </div>
                <?php unset($_SESSION['exist']);
            } ?>

                <div class="data-body">

                    <div class="table-header">

                        <div class="header-info">
                            <h2>Accounts</h2>

                            <div class="btns">
                                <button id="accadd" class="add"><img src="images/add.png" alt="">Add Account</button>
                                <button id="delete"><img src="images/delete.png">Delete</button>
                                <button id="selectall"><img src="images/selectall.png" alt="">Select All</button>
                            </div>
                        </div>

                        <div class="search">
                            <input type="text" id="search" placeholder="Search">
                        </div>

                    </div>

                    <form action="delete/deleteacc.php" id="deleteacc" method="post" class="form-table">
                        <table id="table">
                            <tr id="head">
                                <th></th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>

                            <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="account_id[]" value="<?php echo $row['account_id']; ?>"
                                        class="checkbox"></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $role = ($row['role'] == 1) ? "Admin" : (($row['role'] == 2) ?
                                        "Worker" :  "Supplier"); ?></td>
                                <td id="action"> <button class="edit" data-accid="<?php echo $row['account_id']; ?>"
                                        data-username="<?php echo $row['username']; ?>"
                                        data-password="<?php echo $row['password']; ?>"
                                        data-role="<?php echo $row['role']; ?>"><img src="images/edit.png">Edit</button>
                                </td>
                            </tr>
                            <?php $row = mysqli_fetch_array($result);
                        } ?>

                        </table>
                    </form>

                    <div class="alert-body" id="alert-body">
                        <div class="alert-container">
                            <img src="images/warning.png">
                            <div class="text-warning">
                                <p>Are you sure you want to delete?<br>(All users connected to this account will
                                    also be
                                    delete)</p>
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
                                    echo "href=users.php?page_number=" . $previouspage;
                                } ?>>&laquo;</a></li>

                            <?php for ($i = $starting_page; $i <= $ending_page; $i++) { ?>
                            <li><a href="<?php echo "users.php?page_number=" . $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php } ?>


                            <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                    echo "href=users.php?page_number=" . $nextpage;
                                } ?>>&raquo;</a></li>
                        </ul>

                        </d>

                    </div>

                    <div class="modal-account">
                        <?php include 'modal/account_modal.php'; ?>
                    </div>

                </div>

            </div>
    </body>

    <script src="javascript/navigation.js"></script>
    <script src="javascript/users.js"></script>

</html>