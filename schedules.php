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

$sql = "SELECT `transaction`.*, products.name, suppliers.f_name, suppliers.l_name
FROM `transaction`
LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
LEFT JOIN products on `transaction`.`product_id` = products.product_id
LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(supplier_id) as total from suppliers;";
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

                <div class="form" id="form">

                    <div class="form-container">

                        <div class="header-form">
                            <h2>Add Delivery Schedule</h2>
                            <p id="closebtn">&#10006;</p>
                        </div>

                        <form action="add/addtransaction.php" method="post" id="form-body" class="schedule_add">

                            <div class="input-body">
                                <label for="transaction-date">Transaction Date</label>
                                <input type="date" id="transaction-date" name="transaction_date">
                                <p class="emptyinput" id="transactionerr">Transaction Date cannot be blank</p>
                            </div>

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
                            <button id="schedadd" class="add">Add Delivery Schedule</button>
                            <button id="selectall">Select All</button>
                            <button id="delete">Delete</button>
                        </div>

                        <div class="search">
                            <input type="text" id="search" placeholder="Search">
                        </div>

                    </div>

                    <?php if (isset($_SESSION['added'])) { ?>
                    <div class="prodadded">
                        <p><span>&#10003;</span> <?php echo $_SESSION['added']; ?></p>
                        <p id="alert-close">&#10006;</p>
                    </div>
                    <?php unset($_SESSION['added']);
                } else if (isset($_SESSION['deleted'])) { ?>
                    <div class="proddeleted">
                        <p><span>&#10003;</span> <?php echo $_SESSION['deleted']; ?></p>
                        <p id="alert-close">&#10006;</p>
                    </div>
                    <?php unset($_SESSION['deleted']);
                } else if (isset($_SESSION['updated'])) { ?>
                    <div class="produpdated">
                        <p><span>&#10003;</span> <?php echo $_SESSION['updated']; ?></p>
                        <p id="alert-close">&#10006;</p>
                    </div>
                    <?php unset($_SESSION['updated']);
                } else if (isset($_SESSION['receive'])) { ?>
                    <div class="prodreceive">
                        <p><span>&#10003;</span> <?php echo $_SESSION['receive']; ?></p>
                        <p id="alert-close">&#10006;</p>
                    </div>
                    <?php unset($_SESSION['receive']);
                } ?>

                    <table id="table">
                        <tr id="head">
                            <th></th>
                            <th>Supplier Name</th>
                            <th>Transaction Date</th>
                            <th>Delivery Schedule</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                        <form action="delete/deletesched.php" id="deletesched" method="post">
                            <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="transaction_id[]"
                                        value="<?php echo $row['transaction_id']; ?>" class="checkbox"></td>
                                <td><?php echo $row['f_name'] . " " . $row['l_name']; ?></td>
                                <td><?php echo $row['transaction_date']; ?></td>
                                <td><?php echo $row['delivery_schedule']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td id="action"> <button class="edit"
                                        data-transactionid="<?php echo $row['transaction_id']; ?>"
                                        data-fname="<?php echo $row['f_name']; ?>"
                                        data-lname="<?php echo $row['l_name']; ?>"
                                        data-supplierid="<?php echo $row['supplier_id']; ?>"
                                        data-quantity="<?php echo $row['quantity']; ?>"
                                        data-prodid="<?php echo $row['product_id']; ?>"
                                        data-prodname="<?php echo $row['name']; ?>"
                                        data-transaction="<?php echo $row['transaction_date']; ?>"
                                        data-delivery="<?php echo $row['delivery_schedule']; ?>">Edit</button>
                                    <button id="receive" class="receive"
                                        data-id="<?php echo $row['transaction_id']; ?>">Receive</button>
                                </td>

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

                                <?php $row = mysqli_fetch_array($result);
                        } ?>
                            </tr>
                        </form>

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

                    </table>

                    <ul class="page">
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

                <div class="modal-transaction">
                    <?php include 'modal/transaction_modal.php'; ?>
                </div>

            </div>


        </div>
    </body>

    <script src="javascript/admin.js"></script>
    <script>
    let form = document.getElementById("form");
    let openform = document.getElementById("schedadd");
    let closebtn = document.getElementById("closebtn");
    let closealert = document.getElementById("alert-close");
    let reset = document.getElementById("reset");
    const deletebtn = document.querySelector("#delete");
    let canceldelete = document.getElementById("close-deletion");
    let alertbody = document.getElementById("alert-body");
    let add = document.getElementById("add");
    let transaction_date = document.getElementById("transaction-date");
    let delivery_date = document.getElementById("delivery-date");
    let quantity = document.getElementById("quantity");
    let supplier = document.getElementById("supplier");
    let product = document.getElementById("product");
    let transactionerr = document.getElementById("transactionerr");
    let deliveryerr = document.getElementById("deliveryerr");
    let quantityerr = document.getElementById("quantityerr");
    let suppliererr = document.getElementById("suppliererr");
    let producterr = document.getElementById("producterr");
    let del = document.getElementById("del");
    const edit = document.querySelectorAll(".edit");
    let modal = document.querySelector(".modal-transaction");
    let cancel = document.getElementById("cancel");
    let update = document.getElementById("update");
    let selectall = document.getElementById('selectall');
    let checkboxes = document.querySelectorAll(".checkbox")
    let receivebtns = document.querySelectorAll(".receive");
    let cancelreceive = document.getElementById("close-receive");
    let receivebody = document.getElementById("receive-body");
    let delsched = document.getElementById("del-sched");

    receivebtns.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            receivebody.classList.toggle("receive-body");
            receivebody.classList.toggle("receive-body-show");
            let url = element.getAttribute("data-id");
            delsched.href = "receive.php?id=" + url;
        })
    })

    selectall.addEventListener("click", () => {
        checkboxes.forEach((element) => {

            if (element.checked == false) {
                element.checked = true;
            }
        })
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

    cancel.addEventListener("click", (event) => {
        event.preventDefault();
        modal.classList.toggle("modal-transaction-show");
        modal.classList.toggle("modal-transaction");
    })

    edit.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let transactionid = element.getAttribute("data-transactionid");
            let f_name = element.getAttribute("data-fname");
            let l_name = element.getAttribute("data-lname");
            let quantity = element.getAttribute("data-quantity");
            let transaction = element.getAttribute("data-transaction");
            let delivery = element.getAttribute("data-delivery");
            let prod_id = element.getAttribute("data-prodid");
            let supplier_id = element.getAttribute("data-supplierid");
            let prod_name = element.getAttribute("data-prodname");

            let transaction_id = document.getElementById("transaction-id");
            let transaction_date = document.getElementById("transac-date");
            let delivery_date = document.getElementById("deliver-date");
            let transac_quantity = document.getElementById("quant");
            let selected_supplier = document.getElementById("selected-supplier");
            let selected_product = document.getElementById("selected-product");

            transaction_id.value = transactionid;
            transaction_date.value = transaction;
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

    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
    })

    del.addEventListener("click", () => {
        const deletesched = document.getElementById("deletesched");
        deletesched.submit();
    })

    if (closealert) {
        closealert.addEventListener("click", () => {
            if (document.querySelector(".prodadded")) {
                document.querySelector(".prodadded").style.display = "none";
            }

            if (document.querySelector(".proddeleted")) {
                document.querySelector(".proddeleted").style.display = "none";
            }

            if (document.querySelector(".produpdated")) {
                document.querySelector(".produpdated").style.display = "none";
            }

            if (document.querySelector(".prodreceive")) {
                document.querySelector(".prodreceive").style.display = "none";
            }
        })
    }

    reset.addEventListener("click", (event) => {
        event.preventDefault();
        transaction_date.value = "";
        delivery_date.value = "";
        quantity.value = "";
        supplier.value = "";
        product.value = "";
        transactionerr.display = "none";
        deliveryerr.style.display = "none";
        quantityerr.style.display = "none";
        suppliererr.style.display = "none";
        producterr.style.display = "none";
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

        if (transaction_date.value == "" && delivery_date.value == "" && quantity.value == "" && supplier
            .value == "" && product.value == "") {
            event.preventDefault();
            transactionerr.style.display = "block";
            deliveryerr.style.display = "block";
            quantityerr.style.display = "block";
            suppliererr.style.display = "block";
            producterr.style.display = "block";
        }

        if (transaction_date.value == "") {
            event.preventDefault();
            transactionerr.style.display = "block";
        } else {
            transactionerr.style.display = "none";
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

    update.addEventListener("click", (event) => {

        let fnameerr = document.getElementById("fnameerror");
        let lnameerr = document.getElementById("lnameerror");
        let numbererr = document.getElementById("numerr");
        let companyerr = document.getElementById("companyerror");

        let fname = document.getElementById("supplier-fname");
        let lname = document.getElementById("supplier-lname");
        let supplier_num = document.getElementById("supplier-number");
        let supplier_company = document.getElementById("supplier-company");

        if (fname.value == "" && lname.value == "" && supplier_num.value == "" && supplier_company.value ==
            "") {
            event.preventDefault();
            fnameerr.style.display = "block";
            lnameerr.style.display = "block";
            companyerr.style.display = "block";
            numbererr.style.display = "block";
        }

        if (fname.value == "") {
            event.preventDefault();
            fnameerr.style.display = "block";
        } else {
            fnameerr.style.display = "none";
        }

        if (lname.value == "") {
            event.preventDefault();
            lnameerr.style.display = "block";
        } else {
            lnameerr.style.display = "none";
        }

        if (supplier_num.value == "") {
            event.preventDefault();
            numbererr.style.display = "block";
        } else {
            numbererr.style.display = "none";
        }

        if (supplier_company.value == "") {
            event.preventDefault();
            companyerr.style.display = "block";
        } else {
            companyerr.style.display = "none";
        }

    })
    </script>

</html>