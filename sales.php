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

$sql = "SELECT sales.sale_id,sales.product_id ,sales.sale_date, products.name, sales.quantity * products.price as sale, sales.quantity
from sales join products on sales.product_id = products.product_id
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
                    <li><a href="">Sales</a></li>
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
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date">
                            <p class="emptyinput" id="dateerr">Date cannot be blank</p>
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
                        <input type="text" id="search" placeholder="Search">
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



                <table id="table">
                    <tr id="head">
                        <th></th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Quantity</th>
                        <th>Income</th>
                        <th>Action</th>
                    </tr>
                    <form action="delete/deletesales.php" id="deletesales" method="post">
                        <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="sale_id[]" value="<?php echo $row['sale_id']; ?>" class="checkbox"></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['sale_date']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo $row['sale']; ?></td>
                                <td id="action"> <button class="edit" data-id="<?php echo $row['sale_id'];  ?>" data-date="<?php echo $row['sale_date']; ?>" data-quantity="<?php echo $row['quantity']; ?>" data-currquantity="<?php echo $row['quantity']; ?>" data-prodid="<?php echo $row['product_id']; ?>" data-prodname="<?php echo $row['name']; ?>"><img src="images/edit.png" alt="">Edit</button>
                                </td>

                            <?php $row = mysqli_fetch_array($result);
                        } ?>

                            </tr>
                    </form>

                    <div class="alert-body" id="alert-body">
                        <div class="alert-container">
                            <img src="images/warning.png" alt="dsadadsa">
                            <div class="text-warning">
                                <p>Are you sure you want to delete?
                            </div>
                            <div class="buttons-alert">
                                <button id="del">Delete</button>
                                <button id="close-deletion">Cancel</button>
                            </div>
                        </div>
                    </div>
                </table>

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

<script src="javascript/admin.js"></script>
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
    let date = document.getElementById("date");
    let proderr = document.getElementById("proderr");
    let quantityerr = document.getElementById("quantityerr");
    let loc = document.getElementById("location");
    let locerr = document.getElementById("locerr");
    let dateerr = document.getElementById("dateerr");
    const edit = document.querySelectorAll(".edit");
    let modal = document.querySelector(".modal-sales");
    let cancel = document.getElementById("cancel");
    let del = document.getElementById("del");
    let update = document.getElementById("update");
    let selectall = document.getElementById('selectall');
    let checkboxes = document.querySelectorAll(".checkbox")

    selectall.addEventListener("click", () => {
        checkboxes.forEach((element) => {

            if (element.checked == false) {
                element.checked = true;
            }
        })
    })

    if (canceldelete) {
        canceldelete.addEventListener("click", () => {
            alertbody.classList.toggle("alert-body-show");
            alertbody.classList.toggle("alert-body");
        })
    }

    cancel.addEventListener("click", (event) => {
        event.preventDefault();
        modal.classList.toggle("modal-sales-show");
        modal.classList.toggle("modal-sales");
    })

    edit.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let id = element.getAttribute("data-id");
            let data_date = element.getAttribute("data-date");
            let data_quantity = element.getAttribute("data-quantity");
            let data_currquantity = element.getAttribute("data-currquantity");
            let prodid = element.getAttribute("data-prodid");
            let prodname = element.getAttribute("data-prodname");

            let name = document.getElementById("select-value");
            let date = document.getElementById("date-value");
            let quantity = document.getElementById("quantity-value");
            let sale_id = document.getElementById("sale-id");
            let currquantity = document.getElementById("curr-quantity");
            let selected = document.getElementById("selected");

            sale_id.value = id;
            date.value = data_date;
            quantity.value = data_quantity;
            currquantity.value = data_currquantity;
            selected.value = prodid
            selected.innerHTML = prodname;

            modal.classList.toggle("modal-sales");
            modal.classList.toggle("modal-sales-show");
        })
    })

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
    } else if (document.querySelector(".added")) {
        document.querySelector(".added").addEventListener("animationend", () => {
            document.querySelector(".added").style.display = "none";
        })
    } else if (document.querySelector(".deleted")) {
        document.querySelector(".deleted").addEventListener("animationend", () => {
            document.querySelector(".deleted").style.display = "none";
        })
    } else if (document.querySelector(".emptystocks")) {
        document.querySelector(".emptystocks").addEventListener("animationend", () => {
            document.querySelector(".emptystocks").style.display = "none";
        })
    }


    reset.addEventListener("click", (event) => {
        event.preventDefault();
        selectprod.value = "";
        date.value = "";
        quantity.value = "";
        proderr.style.display = "none";
        quantityerr.style.display = "none";
        dateerr.style.display = "none";
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

        if (event.target.id == "alert-body" && alertbbody.classList.contains("alert-body-show")) {
            alertbbody.classList.toggle("alert-body-show");
            alertbbody.classList.toggle("alert-body");
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

        if (selectprod.value == "" && quantity.value == "" && date.value == "") {
            event.preventDefault();
            proderr.style.display = "block";
            quantityerr.style.display = "block";
            dateerr.style.display = "block";
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

        if (date.value == "") {
            event.preventDefault();
            dateerr.style.display = "block";
        } else {
            dateerr.style.display = "none";
        }
    })

    update.addEventListener("click", (event) => {

        let iderr = document.getElementById("iderr");
        let daterror = document.getElementById("dateerror");
        let quantityerr = document.getElementById("quanterr");

        let name = document.getElementById("prodselect");
        let date = document.getElementById("date-value");
        let quantity = document.getElementById("quantity-value");

        if (name.value == "" && date.value == "" && quantity.value == "") {
            event.preventDefault();
            iderr.style.display = "block";
            daterror.style.display = "block";
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

        if (date.value == "") {
            event.preventDefault();
            daterror.style.display = "block";
        } else {
            daterror.style.display = "none";
        }

    })
</script>

</html>