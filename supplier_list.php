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

$sql = "SELECT suppliers.*, accounts.username
FROM suppliers
LEFT join accounts on suppliers.account_id = accounts.account_id
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

$acc_sql = "SELECT accounts.username, accounts.account_id
FROM accounts
left join suppliers on accounts.account_id = suppliers.account_id
left join workers on accounts.account_id = workers.account_id
where suppliers.account_id is null and workers.account_id is null;";
$stmt_acc = mysqli_prepare($conn, $acc_sql);
mysqli_stmt_execute($stmt_acc);
$result_acc = mysqli_stmt_get_result($stmt_acc);
$row_acc = mysqli_fetch_array($result_acc);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
        <title>Suppliers List</title>
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
                            <h2>Add Supplier</h2>
                            <p id="closebtn">&#10006;</p>
                        </div>

                        <form action="add/addsupplier.php" method="post" id="form-body" class="supplier_add">

                            <div class="input-body">
                                <label for="fname">First Name</label>
                                <input type="text" id="fname" name="fname">
                                <p class="emptyinput" id="fnameerr">First Name cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="lname">Last Name</label>
                                <input type="text" id="lname" name="lname">
                                <p class="emptyinput" id="lnameerr">Last Name cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="number">Contact Number</label>
                                <input type="number" id="number" name="number">
                                <p class="emptyinput" id="numbererr">Number cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="company">Company</label>
                                <input type="text" id="company" name="company">
                                <p class="emptyinput" id="companyerr">Company cannot be blank</p>
                            </div>

                            <div class="input-body">

                                <label for="account-id">Account</label>
                                <select name="account-id" id="account-id">
                                    <option value="">Select An Account</option>
                                    <?php while ($row_acc) { ?>
                                    <option value="<?php echo $row_acc['account_id']; ?>">
                                        <?php echo $row_acc['username']; ?></option>
                                    <?php $row_acc = mysqli_fetch_array($result_acc);
                                } ?>
                                </select>

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
                            <h2>Supplier</h2>
                            <button id="supplieradd" class="add">Add Supplier</button>
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
                } ?>

                    <table id="table">
                        <tr id="head">
                            <th></th>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Company Name</th>
                            <th>Account ID</th>
                            <th>Action</th>
                        </tr>
                        <form action="delete/deletesupplier.php" id="deletesupplier" method="post">
                            <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="supplier_id[]"
                                        value="<?php echo $row['supplier_id']; ?>" class="checkbox"></td>
                                <td><?php echo $row['f_name'] . " " . $row['l_name']; ?></td>
                                <td><?php echo $row['contact_number']; ?></td>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo $row['account_id']; ?></td>
                                <td id="action"> <button class="edit"
                                        data-supplierid="<?php echo $row['supplier_id']; ?>"
                                        data-fname="<?php echo $row['f_name']; ?>"
                                        data-lname="<?php echo $row['l_name']; ?>"
                                        data-number="<?php echo $row['contact_number']; ?>"
                                        data-company="<?php echo $row['company_name']; ?>"
                                        data-accid="<?php echo $row['account_id']; ?>"
                                        data-username="<?php echo $row['username']; ?>">Edit</button>

                                </td>

                                <?php $row = mysqli_fetch_array($result);
                        } ?>
                            </tr>
                        </form>

                        <div class="alert-body" id="alert-body">
                            <div class="alert-container">
                                <img src="images/warning.png">
                                <div class="text-warning">
                                    <p>Are you sure you want to delete?<br>(All transaction from supplier will also be
                                        deleted)
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
                                echo "href=supplier_list.php?page_number=" . $previouspage;
                            } ?>>&laquo;</a></li>

                        <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                        <li><a href="<?php echo "supplier_list.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
                        </li>
                        <?php } ?>


                        <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                echo "href=supplier_list.php?page_number=" . $nextpage;
                            } ?>>&raquo;</a></li>
                    </ul>

                </div>

                <div class="modal-supplier">
                    <?php include 'modal/supplier_modal.php'; ?>
                </div>

            </div>

        </div>
    </body>

    <script src="javascript/admin.js"></script>
    <script>
    let form = document.getElementById("form");
    let openform = document.getElementById("supplieradd");
    let closebtn = document.getElementById("closebtn");
    let closealert = document.getElementById("alert-close");
    let reset = document.getElementById("reset");
    let deletebtn = document.querySelector("#delete");
    let canceldelete = document.getElementById("close-deletion");
    let alertbody = document.getElementById("alert-body");
    let add = document.getElementById("add");
    let fname = document.getElementById("fname");
    let lname = document.getElementById("lname");
    let number = document.getElementById("number");
    let company = document.getElementById("company");
    let accid = document.getElementById("account-id");
    let fnameerr = document.getElementById("fnameerr");
    let lnameerr = document.getElementById("lnameerr");
    let numbererr = document.getElementById("numbererr");
    let companyerr = document.getElementById("companyerr");
    let del = document.getElementById("del");
    const edit = document.querySelectorAll(".edit");
    let modal = document.querySelector(".modal-supplier");
    let cancel = document.getElementById("cancel");
    let update = document.getElementById("update");
    let checkboxes = document.querySelectorAll(".checkbox");
    let selectall = document.getElementById('selectall');

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
        modal.classList.toggle("modal-supplier-show");
        modal.classList.toggle("modal-supplier");
    })

    edit.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let supplierid = element.getAttribute("data-supplierid");
            let f_name = element.getAttribute("data-fname");
            let l_name = element.getAttribute("data-lname");
            let number = element.getAttribute("data-number");
            let company = element.getAttribute("data-company");
            let username = element.getAttribute("data-username");
            let acc_id = element.getAttribute("data-accid");

            let suppid = document.getElementById("supplier-id");
            let fname = document.getElementById("supplier-fname");
            let lname = document.getElementById("supplier-lname");
            let supplier_num = document.getElementById("supplier-number");
            let supplier_company = document.getElementById("supplier-company");
            let selected = document.getElementById("selected");

            suppid.value = supplierid;
            fname.value = f_name;
            lname.value = l_name;
            supplier_num.value = number;
            supplier_company.value = company;
            selected.value = acc_id;
            selected.innerHTML = username;

            modal.classList.toggle("modal-supplier");
            modal.classList.toggle("modal-supplier-show");
        })
    })

    del.addEventListener("click", () => {
        const deletesupplier = document.getElementById("deletesupplier");
        deletesupplier.submit();
    })

    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
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
        })
    }

    reset.addEventListener("click", (event) => {
        event.preventDefault();
        fname.value = "";
        lname.value = "";
        number.value = "";
        company.value = "";
        accid.value = "";
        fnameerrstyle.display = "none";
        lnameerr.style.display = "none";
        numbererr.style.display = "none";
        companyerr.style.display = "none";
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

        if (event.target.classList == "modal-supplier-show") {
            modal.classList.toggle("modal-supplier-show");
            modal.classList.toggle("modal-supplier");
        }
    })

    closebtn.addEventListener("click", () => {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    })

    add.addEventListener("click", (event) => {

        if (fname.value == "" && lname.value == "" && company.value == "" && number.value == "") {
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

        if (number.value == "") {
            event.preventDefault();
            numbererr.style.display = "block";
        } else if (number.value.length <
            11) {
            event.preventDefault();
            numbererr.innerHTML = "Number length must not be below 11";
            numbererr.style.display = "block";
        } else if (number.value.length > 11) {
            event.preventDefault();
            numbererr.innerHTML = "Number length must not exceed to 11";
            numbererr.style.display = "block";
        } else {
            numbererr.style.display = "none";
        }

        if (company.value == "") {
            event.preventDefault();
            companyerr.style.display = "block";
        } else {
            companyerr.style.display = "none";
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