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

$sql = "SELECT products.name, stocks.*
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

$sql3 = "SELECT products.product_id, products.name
FROM products
left join stocks on products.product_id = stocks.product_id
where stocks.quantities  = 0 or stocks.quantities = null or stocks.stock_id is null";
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
        <link rel="shortcut icon" href="images/logo.jpg" type="image/x-icon">
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

                <img src="images/logo.jpg" alt="logo">
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
                        <li><a href="supplier.php">Account</a></li>
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
                            <h2>Add Stock</h2>
                            <p id="closebtn">&#10006;</p>
                        </div>

                        <form action="add/addstocks.php" method="post" id="form-stock">

                            <div class="input-body">
                                <label for="selectprod">Product Name</label>
                                <select name="prodid" id="selectprod">
                                    <option value="">Select Product</option>
                                    <?php while ($row_product) { ?>
                                    <option value="<?php echo $row_product['product_id']; ?>">
                                        <?php echo $row_product['name']; ?></option>
                                    <?php $row_product = mysqli_fetch_array($result3);
                                } ?>
                                </select>
                                <p class="emptyinput" id="proderr">Product cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="quantities">Quantities</label>
                                <input type="number" id="quantities" name="quantities">
                                <p class="emptyinput" id="quantityerr">Quantities cannot be blank</p>
                            </div>

                            <div class="buttons">
                                <button type="submit" id="add" name="add">Add</button>
                                <button id="reset">Reset</button>
                            </div>

                        </form>

                    </div>

                </div>

                <div class="stock-list">

                    <div class="table-header">

                        <div class="header-info">
                            <h2>Inventory</h2>
                            <?php if (
                            isset($_SESSION['admin']) || isset($_SESSION['admin_username']) &&
                            isset($_SESSION['worker']) || isset($_SESSION['worker_username'])
                        ) { ?>
                            <div class="btns">
                                <button id="stockadd" class="add"><img src="images/add.png" alt="">Add Stock</button>
                                <button id="delete"><img src="images/delete.png">Delete</button>
                                <button id="selectall"><img src="images/selectall.png" alt="">Select All</button>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="search">
                            <input type="text" id="search" placeholder="Search" name="name">
                        </div>

                    </div>

                    <?php if (isset($_SESSION['added'])) { ?>
                    <div class="added">
                        <p><span>&#10003;</span> <?php echo $_SESSION['added']; ?></p>

                    </div>
                    <?php unset($_SESSION['added']);
                } else if (isset($_SESSION['deleted'])) { ?>
                    <div class="deleted">
                        <p><span>&#10003;</span> <?php echo $_SESSION['deleted']; ?></p>
                    </div>
                    <?php unset($_SESSION['deleted']);
                } ?>

                    <form action="delete/deletestocks.php" id="deletestocks" method="post" class="form-table">
                        <table id="table">
                            <tr id="head">
                                <?php if (
                                isset($_SESSION['admin']) || isset($_SESSION['admin_username']) &&
                                isset($_SESSION['worker']) || isset($_SESSION['worker_username'])
                            ) { ?>
                                <th></th>
                                <?php } ?>
                                <th>Product Name</th>
                                <th>Quantities</th>
                                <th>Stock In</th>
                                <th>Stock Out</th>
                            </tr>

                            <?php while ($row) { ?>
                            <tr>
                                <?php if (
                                    isset($_SESSION['admin']) || isset($_SESSION['admin_username']) &&
                                    isset($_SESSION['worker']) || isset($_SESSION['worker_username'])
                                ) { ?>
                                <td><input type="checkbox" name="stock_id[]" value="<?php echo $row['stock_id']; ?>"
                                        class="checkbox"></td>
                                <?php } ?>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['quantities']; ?></td>
                                <td><?php echo $row['stock_in']; ?></td>
                                <td><?php echo $row['stock_out']; ?></td>
                            </tr>
                            <?php $row = mysqli_fetch_array($result);
                        } ?>
                        </table>
                    </form>

                    <div class="alert-body" id="alert-body">
                        <div class="alert-container">
                            <img src="images/warning.png">
                            <div class="text-warning">
                                <p>Are you sure you want to delete?(Stocks, sales and transactions will also be
                                    deleted)</p>
                            </div>
                            <div class="buttons-alert">
                                <button id="del">Delete</button>
                                <button id="close-deletion">Cancel</button>
                            </div>
                        </div>
                    </div>



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

    <script src="javascript/navigation.js"></script>
    <script>
    let form = document.getElementById("form");
    let openform = document.getElementById("stockadd");
    let closebtn = document.getElementById("closebtn");
    let reset = document.getElementById("reset");
    let deletebtn = document.querySelector("#delete");
    let canceldelete = document.getElementById("close-deletion");
    let alertbody = document.getElementById("alert-body");
    let add = document.getElementById("add");
    let selectprod = document.getElementById("selectprod");
    let quantities = document.getElementById("quantities");
    let proderr = document.getElementById("proderr");
    let quantityerr = document.getElementById("quantityerr");
    let del = document.getElementById("del");
    let selectall = document.getElementById('selectall');
    let cancel = document.getElementById("cancel");

    function Checkboxes() {
        let checkboxes = document.querySelectorAll(".checkbox");
        for (box of checkboxes) {
            if (box.checked == false) {
                return true;
            }
        }
        return false;
    }

    function AttachedEvents() {
        let selectall = document.getElementById('selectall');
        let checkboxes = document.querySelectorAll(".checkbox");

        selectall.addEventListener("click", () => {
            if (Checkboxes()) {
                checkboxes.forEach((element) => {
                    element.checked = true;
                })
            } else {
                checkboxes.forEach((element) => {
                    element.checked = false;
                })
            }
        })
    }

    AttachedEvents();

    search.addEventListener("input", () => {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementById("table").innerHTML = this.responseText;
            AttachedEvents();
        }
        xhttp.open("GET", "search/search_inventory.php?name=" + search.value);
        xhttp.send();
    })

    if (canceldelete) {
        canceldelete.addEventListener("click", () => {
            alertbody.classList.toggle("alert-body-show");
            alertbody.classList.toggle("alert-body");
        })
    }

    if (deletebtn) {
        deletebtn.addEventListener("click", (event) => {
            event.preventDefault();
            alertbody.classList.toggle("alert-body");
            alertbody.classList.toggle("alert-body-show");
        })
    }

    del.addEventListener("click", () => {
        const deletestocks = document.getElementById("deletestocks");
        deletestocks.submit();
    })


    if (document.querySelector(".added")) {
        document.querySelector(".added").addEventListener("animationend", () => {
            document.querySelector(".added").style.display = "none";
        })

        document.querySelector(".added").addEventListener("click", () => {
            document.querySelector(".added").style.display = "none";
        })
    } else if (document.querySelector(".deleted")) {
        document.querySelector(".deleted").addEventListener("animationend", () => {
            document.querySelector(".deleted").style.display = "none";
        })

        document.querySelector(".deleted").addEventListener("click", () => {
            document.querySelector(".deleted").style.display = "none";
        })
    }


    reset.addEventListener("click", (event) => {
        event.preventDefault();
        selectprod.value = "";
        quantities.value = "";
        proderr.style.display = "none";
        quantityerr.style.display = "none";
    })

    if (openform) {
        openform.addEventListener("click", () => {
            form.classList.toggle("form");
            form.classList.toggle("show-form");
        })

    }

    window.addEventListener("resize", () => {
        if (window.innerWidth > 1022 && form.classList.contains("show-form")) {
            form.classList.toggle("show-form");
            form.classList.toggle("form");
        }
    })

    window.addEventListener("click", (event) => {

        if (event.target.id == "form" && form.classList.contains("show-form")) {
            form.classList.toggle("show-form");
            form.classList.toggle("form");
        }

        if (event.target.id == "alert-body" && alertbody.classList.contains("alert-body-show")) {
            alertbody.classList.toggle("alert-body-show");
            alertbody.classList.toggle("alert-body");
        }

    })

    closebtn.addEventListener("click", () => {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    })

    add.addEventListener("click", (event) => {

        if (selectprod.value == "" && quantities.value == "") {
            event.preventDefault();
            proderr.style.display = "block";
            quantityerr.style.display = "block";
        }

        if (selectprod.value == "") {
            event.preventDefault();
            proderr.style.display = "block";
        } else {
            proderr.style.display = "none";
        }

        if (quantities.value == "") {
            event.preventDefault();
            quantityerr.style.display = "block";
        } else {
            quantityerr.style.display = "none";
        }

    })
    </script>


</html>