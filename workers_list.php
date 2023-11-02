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

$sql = "SELECT workers.*, accounts.username
FROM workers
LEFT join accounts on workers.account_id = accounts.account_id
LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(worker_id) as total from workers;";
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
        <title>Workers List</title>
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
                        <li><a href="">List of Workers</a></li>
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
                            <h2>Add Worker</h2>
                            <p id="closebtn">&#10006;</p>
                        </div>

                        <form action="add/addworker.php" method="post" id="form-body">

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
                            <h2>Workers</h2>

                            <div class="btns">
                                <button id="workeradd" class="add"><img src="images/add.png" alt="">Add
                                    Worker</button>
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
                } ?>

                    <table id="table">
                        <tr id="head">
                            <th></th>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Account ID</th>
                            <th>Action</th>
                        </tr>
                        <form action="delete/deleteworker.php" id="deleteworker" method="post">
                            <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="worker_id[]" value="<?php echo $row['worker_id']; ?>"
                                        class="checkbox"></td>
                                <td><?php echo $row['f_name'] . " " . $row['l_name']; ?></td>
                                <td><?php echo $row['contact_number'] . $total_pages; ?></td>
                                <td><?php echo $row['account_id']; ?></td>
                                <td id="action"> <button class="edit" data-workerid="<?php echo $row['worker_id']; ?>"
                                        data-fname="<?php echo $row['f_name']; ?>"
                                        data-lname="<?php echo $row['l_name']; ?>"
                                        data-number="<?php echo $row['contact_number']; ?>"
                                        data-accid="<?php echo $row['account_id']; ?>"
                                        data-username="<?php echo $row['username']; ?>"><img src="images/edit.png"
                                            alt="">Edit</button>
                                </td>

                                <?php $row = mysqli_fetch_array($result);
                        } ?>
                            </tr>
                        </form>

                        <div class="alert-body" id="alert-body">
                            <div class="alert-container">
                                <img src="images/warning.png">
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
                        <p><?php echo "Page " . "<b>$page_number </b>" . " of " . "<b>$total_pages</b>" ?>
                        <ul class="page-list">
                            <li><a <?php if ($page_number != 1) {
                                    echo "href=worker_list.php?page_number=" . $previouspage;
                                } ?>>&laquo;</a></li>

                            <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                            <li><a
                                    href="<?php echo "worker_list.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
                            </li>
                            <?php } ?>


                            <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                    echo "href=worker_list.php?page_number=" . $nextpage;
                                } ?>>&raquo;</a></li>
                        </ul>

                    </div>

                </div>

                <div class="modal-worker">
                    <?php include 'modal/worker_modal.php'; ?>
                </div>

            </div>


        </div>
    </body>

    <script src="javascript/admin.js"></script>
    <script>
    let form = document.getElementById("form");
    let openform = document.getElementById("workeradd");
    let closebtn = document.getElementById("closebtn");
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
    let del = document.getElementById("del");
    const edit = document.querySelectorAll(".edit");
    let modal = document.querySelector(".modal-worker");
    let cancel = document.getElementById("cancel");
    let update = document.getElementById("update");
    let checkboxes = document.querySelectorAll(".checkbox");

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
        modal.classList.toggle("modal-worker-show");
        modal.classList.toggle("modal-worker");
    })

    edit.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let workerid = element.getAttribute("data-workerid");
            let f_name = element.getAttribute("data-fname");
            let l_name = element.getAttribute("data-lname");
            let number = element.getAttribute("data-number");
            let username = element.getAttribute("data-username");
            let acc_id = element.getAttribute("data-accid");

            let worker_id = document.getElementById("worker-id");
            let fname = document.getElementById("worker-fname");
            let lname = document.getElementById("worker-lname");
            let worker_num = document.getElementById("worker-number");
            let selected = document.getElementById("selected");

            worker_id.value = workerid;
            fname.value = f_name;
            lname.value = l_name;
            worker_num.value = number;
            selected.value = acc_id;
            selected.innerHTML = username;

            modal.classList.toggle("modal-worker");
            modal.classList.toggle("modal-worker-show");
        })
    })

    del.addEventListener("click", () => {
        const deleteworker = document.getElementById("deleteworker");
        deleteworker.submit();
    })

    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
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
    }

    reset.addEventListener("click", (event) => {
        event.preventDefault();
        fname.value = "";
        lname.value = "";
        number.value = "";
        accid.value = "";
        fnameerr.style.display = "none";
        lnameerr.style.display = "none";
        numbererr.style.display = "none";
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

        if (fname.value == "" && lname.value == "" && number.value == "") {
            event.preventDefault();
            fnameerr.style.display = "block";
            lnameerr.style.display = "block";
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