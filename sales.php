<?php

session_start();
include 'openconn.php';

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
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

$sql = "SELECT sales.sale_id,sales.product_id ,sales.sale_date, products.name, sales.quantity * products.price as sale, sales.quantity
from sales join products on sales.product_id = products.product_id
order by sales.sale_date desc
LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(sale_id) as total from sales;";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$Row = mysqli_fetch_array($result2);

$total_records = $Row['total'];
$total_pages = ceil($total_records / $number_per_page);

$prod_sql = "SELECT product_id, `name` from products;";
$stmt_prod = mysqli_prepare($conn, $prod_sql);
mysqli_stmt_execute($stmt_prod);
$result_prod = mysqli_stmt_get_result($stmt_prod);
$row_prod = mysqli_fetch_array($result_prod);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <title>Sales</title>
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
                    strtoupper($_SESSION["admin_username"]) : strtoupper($_SESSION["worker_username"]); ?>
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
                <?php } else { ?>
                    <ul class="menu">
                        <p>Account Details</p>
                        <li><a href="worker.php">Account</a></li>
                    </ul>
                <?php } ?>

                <?php if (
                    isset($_SESSION["admin"]) || isset($_SESSION["admin_username"])
                    || isset($_SESSION["worker"]) || isset($_SESSION["worker_username"])
                ) { ?>
                    <ul class="menu">
                        <p>Products</p>
                        <li><a href="inventory.php">Inventory</a></li>
                        <li><a href="products.php">Product List</a></li>
                        <li><a href="sales.php">Sales</a></li>
                        <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                            <li><a href="expense.php">Expenses</a></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
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
                <?php } ?>

            </div>
        </nav>
    </div>

    <div class="body">

        <div class="body-content">

            <div class="form" id="form">

                <div class="form-container">

                    <div class="header-form">
                        <h2>Add Sales</h2>
                        <p id="closebtn">&#10006;</p>
                    </div>

                    <form action="add/addsales.php" method="post" id="form-body">

                        <div class="input-body">
                            <label for="selectprod">Product Name</label>
                            <select name="prodid" id="selectprod">
                                <option value="">Select Product</option>
                                <?php while ($row_prod) { ?>
                                    <option value="<?php echo $row_prod['product_id']; ?>">
                                        <?php echo $row_prod['name']; ?></option>
                                <?php $row_prod = mysqli_fetch_array($result_prod);
                                } ?>
                            </select>
                            <p class="emptyinput" id="proderr">Product cannot be blank</p>
                        </div>

                        <div class="input-body">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity">
                            <p class="emptyinput" id="quantityerr">Quantity cannot be blank</p>
                        </div>

                        <div class="buttons">
                            <button type="submit" id="add" name="add">Add</button>
                            <button id="reset">Reset</button>
                        </div>

                    </form>

                </div>


            </div>

            <div class="product-list">

                <div class="table-header">

                    <div class="header-info">
                        <h2>Sales</h2>

                        <div class="btns">
                            <button id="saleadd" class="add"><img src="images/add.png" alt="">Add Sales</button>
                            <button id="delete"><img src="images/delete.png">Delete</button>
                            <button id="selectall"><img src="images/selectall.png" alt="">Select All</button>
                        </div>
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
                } else if (isset($_SESSION['updated'])) { ?>
                    <div class="updated">
                        <p><span>&#10003;</span> <?php echo $_SESSION['updated']; ?></p>
                    </div>
                <?php unset($_SESSION['updated']);
                } else if (isset($_SESSION['emptystocks'])) { ?>
                    <div class="emptystocks">
                        <p><span>&#10003;</span> <?php echo $_SESSION['emptystocks']; ?></p>
                    </div>
                <?php unset($_SESSION['emptystocks']);
                } else if (isset($_SESSION['lessquantity'])) { ?>
                    <div class="lessquantity">
                        <p><span>&#10003;</span> <?php echo $_SESSION['lessquantity']; ?></p>
                    </div>
                <?php unset($_SESSION['lessquantity']);
                }
                ?>

                <form action="delete/deletesales.php" id="deletesales" method="post" class="form-table">
                    <table id="table">
                        <tr id="head">
                            <th></th>
                            <th>Product Name</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Income</th>
                            <th>Edit</th>
                        </tr>

                        <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="sale_id[]" value="<?php echo $row['sale_id']; ?>" class="checkbox"></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['sale_date']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo $row['sale']; ?></td>
                                <td id="action"> <button class="edit" data-id="<?php echo $row['sale_id'];  ?>" data-quantity="<?php echo $row['quantity']; ?>" data-currquantity="<?php echo $row['quantity']; ?>" data-prodid="<?php echo $row['product_id']; ?>" data-prodname="<?php echo $row['name']; ?>"><img src="images/edit.png" alt="">Edit</button>
                                </td>
                            </tr>
                        <?php $row = mysqli_fetch_array($result);
                        } ?>

                    </table>
                </form>

                <div class="alert-body" id="alert-body">
                    <div class="alert-container">
                        <img src="images/warning.png" alt="dsadadsa">
                        <div class="text-warning">
                            <p>Are you sure you want to delete the selected items?
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
                                    echo "href=sales.php?page_number=" . $previouspage;
                                } ?>>&laquo;</a></li>

                        <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                            <li><a href="<?php echo "sales.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
                            </li>
                        <?php } ?>


                        <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                    echo "href=sales.php?page_number=" . $nextpage;
                                } ?>>&raquo;</a></li>
                    </ul>

                </div>

            </div>

            <div class="modal-sales">
                <?php include 'modal/sale_modal.php'; ?>
            </div>

        </div>


    </div>
</body>

<script src="javascript/navigation.js"></script>
<script>
    let form = document.getElementById("form");
    let openform = document.getElementById("saleadd");
    let closebtn = document.getElementById("closebtn");
    let reset = document.getElementById("reset");
    let deletebtn = document.querySelector("#delete");
    let canceldelete = document.getElementById("close-deletion");
    let alertbody = document.getElementById("alert-body");
    let add = document.getElementById("add");
    let selectprod = document.getElementById("selectprod");
    let quantity = document.getElementById("quantity");
    let proderr = document.getElementById("proderr");
    let quantityerr = document.getElementById("quantityerr");
    let loc = document.getElementById("location");
    let locerr = document.getElementById("locerr");
    let modal = document.querySelector(".modal-sales");
    let cancel = document.getElementById("cancel");
    let del = document.getElementById("del");
    let search = document.getElementById("search");
    let update = document.getElementById("update");

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
        const edit = document.querySelectorAll(".edit");
        let modal = document.querySelector(".modal-sales");

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

        edit.forEach((element) => {
            element.addEventListener("click", (event) => {
                event.preventDefault();
                let id = element.getAttribute("data-id");
                let data_quantity = element.getAttribute("data-quantity");
                let data_currquantity = element.getAttribute("data-currquantity");
                let prodid = element.getAttribute("data-prodid");
                let prodname = element.getAttribute("data-prodname");

                let name = document.getElementById("select-value");
                let quantity = document.getElementById("quantity-value");
                let sale_id = document.getElementById("sale-id");
                let currquantity = document.getElementById("curr-quantity");
                let selected = document.getElementById("selected");

                sale_id.value = id;
                quantity.value = data_quantity;
                currquantity.value = data_currquantity;
                selected.value = prodid
                selected.innerHTML = prodname;

                modal.classList.toggle("modal-sales");
                modal.classList.toggle("modal-sales-show");
            })

        })
    }

    AttachedEvents();

    search.addEventListener("input", () => {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementById("table").innerHTML = this.responseText;
            AttachedEvents();
        }
        xhttp.open("GET", "search/search_sales.php?name=" + search.value);
        xhttp.send();
    })

    cancel.addEventListener("click", (event) => {
        event.preventDefault();
        modal.classList.toggle("modal-sales-show");
        modal.classList.toggle("modal-sales");
    })

    if (canceldelete) {
        canceldelete.addEventListener("click", () => {
            alertbody.classList.toggle("alert-body-show");
            alertbody.classList.toggle("alert-body");
        })
    }

    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
    })

    del.addEventListener("click", () => {
        const deletesales = document.getElementById("deletesales");
        deletesales.submit();
    })

    if (document.querySelector(".updated")) {
        document.querySelector(".updated").addEventListener("animationend", () => {
            document.querySelector(".updated").style.display = "none";
        })

        document.querySelector(".updated").addEventListener("click", () => {
            document.querySelector(".updated").style.display = "none";
        })
    } else if (document.querySelector(".added")) {
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
    } else if (document.querySelector(".exist")) {
        document.querySelector(".exist").addEventListener("animationend", () => {
            document.querySelector(".exist").style.display = "none";
        })

        document.querySelector(".exist").addEventListener("click", () => {
            document.querySelector(".exist").style.display = "none";
        })
    }

    reset.addEventListener("click", (event) => {
        event.preventDefault();
        selectprod.value = "";
        quantity.value = "";
        proderr.style.display = "none";
        quantityerr.style.display = "none";
    })


    openform.addEventListener("click", () => {
        form.classList.toggle("form");
        form.classList.toggle("show-form");
    })

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

        if (event.target.classList == "modal-sales-show") {
            modal.classList.toggle("modal-sales-show");
            modal.classList.toggle("modal-sales");
        }
    })

    closebtn.addEventListener("click", () => {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    })

    add.addEventListener("click", (event) => {

        if (selectprod.value == "" && quantity.value == "") {
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

        if (quantity.value == "") {
            event.preventDefault();
            quantityerr.style.display = "block";
        } else {
            quantityerr.style.display = "none";
        }

    })

    update.addEventListener("click", (event) => {

        let iderr = document.getElementById("iderr");
        let quantityerr = document.getElementById("quanterr");

        let name = document.getElementById("prodselect");
        let quantity = document.getElementById("quantity-value");

        if (name.value == "" && quantity.value == "") {
            event.preventDefault();
            iderr.style.display = "block";
            quantityerr.style.display = "block";
        }

        if (name.value == "") {
            event.preventDefault();
            iderr.style.display = "block";
        } else {
            iderr.style.display = "none";
        }

        if (quantity.value == "") {
            event.preventDefault();
            quantityerr.style.display = "block";
        } else {
            quantityerr.style.display = "none";
        }
    })
</script>

</html>