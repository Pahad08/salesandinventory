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

                        <form action="add/addtransaction.php" method="post" id="form-body" class="schedule_add">

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

                <div class="product-list">

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
                } else if (isset($_SESSION['receive'])) { ?>
                    <div class="prodreceive">
                        <p><span>&#10003;</span> <?php echo $_SESSION['receive']; ?></p>
                    </div>
                    <?php unset($_SESSION['receive']);
                } ?>

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

                            <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                            <li><a href="<?php echo "schedules.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
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

    <script src="javascript/admin.js"></script>
    <script>
    let form = document.getElementById("form");
    let openform = document.getElementById("schedadd");
    let closebtn = document.getElementById("closebtn");
    let reset = document.getElementById("reset");
    const deletebtn = document.querySelector("#delete");
    let canceldelete = document.getElementById("close-deletion");
    let alertbody = document.getElementById("alert-body");
    let add = document.getElementById("add");
    let delivery_date = document.getElementById("delivery-date");
    let quantity = document.getElementById("quantity");
    let supplier = document.getElementById("supplier");
    let product = document.getElementById("product");
    let deliveryerr = document.getElementById("deliveryerr");
    let quantityerr = document.getElementById("quantityerr");
    let suppliererr = document.getElementById("suppliererr");
    let producterr = document.getElementById("producterr");
    let del = document.getElementById("del");
    let cancel = document.getElementById("cancel");
    let update = document.getElementById("update");
    let cancelreceive = document.getElementById("close-receive");
    let delsched = document.getElementById("del-sched");
    let search = document.getElementById("search");
    let modal = document.querySelector(".modal-transaction");
    let receivebody = document.getElementById("receive-body");

    function AttachedEvents() {
        let selectall = document.getElementById('selectall');
        let checkboxes = document.querySelectorAll(".checkbox");
        const edit = document.querySelectorAll(".edit");
        let modal = document.querySelector(".modal-transaction");
        let receivebtns = document.querySelectorAll(".receive");

        if (receivebtns && receivebody) {
            receivebtns.forEach((element) => {
                element.addEventListener("click", (event) => {
                    event.preventDefault();
                    receivebody.classList.toggle("receive-body");
                    receivebody.classList.toggle("receive-body-show");
                    let url = element.getAttribute("data-id");
                    delsched.href = "receive.php?id=" + url;
                })
            })
        }

        if (selectall && checkboxes) {
            selectall.addEventListener("click", () => {
                checkboxes.forEach((element) => {
                    if (element.checked == false) {
                        element.checked = true;
                    }
                })
            })
        }

        edit.forEach((element) => {
            element.addEventListener("click", (event) => {
                event.preventDefault();
                let transactionid = element.getAttribute("data-transactionid");
                let f_name = element.getAttribute("data-fname");
                let l_name = element.getAttribute("data-lname");
                let quantity = element.getAttribute("data-quantity");
                let delivery = element.getAttribute("data-delivery");
                let prod_id = element.getAttribute("data-prodid");
                let supplier_id = element.getAttribute("data-supplierid");
                let prod_name = element.getAttribute("data-prodname");

                let transaction_id = document.getElementById("transaction-id");
                let delivery_date = document.getElementById("deliver-date");
                let transac_quantity = document.getElementById("quant");
                let selected_supplier = document.getElementById("selected-supplier");
                let selected_product = document.getElementById("selected-product");

                transaction_id.value = transactionid;
                delivery_date.value = delivery;
                transac_quantity.value = quantity;
                selected_supplier.value = supplier_id;
                selected_product.value = prod_id;

                selected_supplier.innerHTML = f_name + " " + l_name;
                selected_product.innerHTML = prod_name;

                modal.classList.toggle("modal-transaction");
                modal.classList.toggle("modal-transaction-show");
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
        xhttp.open("GET", "search/search_sched.php?name=" + search.value);
        xhttp.send();
    })

    if (cancelreceive) {
        cancelreceive.addEventListener("click", (event) => {
            event.preventDefault();
            receivebody.classList.toggle("receive-body-show");
            receivebody.classList.toggle("receive-body");
        })
    }

    if (canceldelete) {
        canceldelete.addEventListener("click", () => {
            alertbody.classList.toggle("alert-body-show");
            alertbody.classList.toggle("alert-body");
        })
    }

    if (cancel) {
        cancel.addEventListener("click", (event) => {
            event.preventDefault();
            modal.classList.toggle("modal-transaction-show");
            modal.classList.toggle("modal-transaction");
        })
    }


    if (deletebtn) {
        deletebtn.addEventListener("click", (event) => {
            event.preventDefault();
            alertbody.classList.toggle("alert-body");
            alertbody.classList.toggle("alert-body-show");
        })
    }

    if (del) {
        del.addEventListener("click", () => {
            const deletesched = document.getElementById("deletesched");
            deletesched.submit();
        })
    }

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
    } else if (document.querySelector(".prodreceive")) {
        document.querySelector(".prodreceive").addEventListener("animationend", () => {
            document.querySelector(".prodreceive").style.display = "none";
        })

        document.querySelector(".prodreceive").addEventListener("click", () => {
            document.querySelector(".prodreceive").style.display = "none";
        })
    }

    reset.addEventListener("click", (event) => {
        event.preventDefault();
        delivery_date.value = "";
        quantity.value = "";
        supplier.value = "";
        product.value = "";
        deliveryerr.style.display = "none";
        quantityerr.style.display = "none";
        suppliererr.style.display = "none";
        producterr.style.display = "none";
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

        if (event.target.classList == "modal-transaction-show") {
            modal.classList.toggle("modal-transaction-show");
            modal.classList.toggle("modal-transaction");
        }
    })

    closebtn.addEventListener("click", () => {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    })

    add.addEventListener("click", (event) => {

        if (delivery_date.value == "" && quantity.value == "" && supplier
            .value == "" && product.value == "") {
            event.preventDefault();
            deliveryerr.style.display = "block";
            quantityerr.style.display = "block";
            suppliererr.style.display = "block";
            producterr.style.display = "block";
        }

        if (delivery_date.value == "") {
            event.preventDefault();
            deliveryerr.style.display = "block";
        } else {
            deliveryerr.style.display = "none";
        }

        if (quantity.value == "") {
            event.preventDefault();
            quantityerr.style.display = "block";
        } else {
            quantityerr.style.display = "none";
        }

        if (supplier.value == "") {
            event.preventDefault();
            suppliererr.style.display = "block";
        } else {
            suppliererr.style.display = "none";
        }

        if (product.value == "") {
            event.preventDefault();
            producterr.style.display = "block";
        } else {
            producterr.style.display = "none";
        }

    })

    if (update) {
        update.addEventListener("click", (event) => {

            let deliveryerr = document.getElementById("delivererr");
            let quanterr = document.getElementById("quanterr");

            let deliverdate = document.getElementById("deliver-date");
            let quantity = document.getElementById("quant");

            if (deliverdate.value == "" && quantity.value == "" && supplier_id.value == "" && prod_id.value ==
                "") {
                event.preventDefault();
                deliveryerr.style.display = "block";
                quanterr.style.display = "block";
                supid_err.style.display = "block";
                prodid_err.style.display = "block";
            }

            if (deliverdate.value == "") {
                event.preventDefault();
                deliveryerr.style.display = "block";
            } else {
                deliveryerr.style.display = "none";
            }

            if (quantity.value == "") {
                event.preventDefault();
                quanterr.style.display = "block";
            } else {
                quanterr.style.display = "none";
            }


        })
    }
    </script>

</html>