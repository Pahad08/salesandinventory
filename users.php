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
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
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

                <div class="product-list">

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
                } else if (isset($_SESSION['exist'])) { ?>
                    <div class="exist">
                        <p><span>&#10003;</span> <?php echo $_SESSION['exist']; ?></p>
                    </div>
                    <?php unset($_SESSION['exist']);
                } ?>

                    <table id="table">
                        <tr id="head">
                            <th></th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        <form action="delete/deleteacc.php" id="deleteacc" method="post">
                            <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="account_id[]" value="<?php echo $row['account_id']; ?>"
                                        class="checkbox"></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['password']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td id="action"> <button class="edit" data-accid="<?php echo $row['account_id']; ?>"
                                        data-username="<?php echo $row['username']; ?>"
                                        data-password="<?php echo $row['password']; ?>"
                                        data-role="<?php echo $row['role']; ?>">Edit</button>
                                </td>

                                <?php $row = mysqli_fetch_array($result);
                        } ?>
                            </tr>
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

                    </table>

                    <div class="page">
                        <p><?php echo "Page " . "<b>$page_number</b>" . " of " . "<b>$total_pages</b>" ?>

                        <ul class="page-list">
                            <li><a <?php if ($page_number != 1) {
                                    echo "href=users.php?page_number=" . $previouspage;
                                } ?>>&laquo;</a></li>

                            <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                            <li><a href="<?php echo "users.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
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

    <script src="javascript/admin.js"></script>
    <script>
    let form = document.getElementById("form");
    let openform = document.getElementById("accadd");
    let closebtn = document.getElementById("closebtn");
    let reset = document.getElementById("reset");
    let deletebtn = document.querySelector("#delete");
    let canceldelete = document.getElementById("close-deletion");
    let alertbody = document.getElementById("alert-body");
    let add = document.getElementById("add");
    let username = document.getElementById("username");
    let password = document.getElementById("password");
    let role = document.getElementById("role");
    let usernameerr = document.getElementById("usernameerr");
    let passworderr = document.getElementById("passworderr");
    let roleerr = document.getElementById("roleerr");
    let del = document.getElementById("del");
    const edit = document.querySelectorAll(".edit");
    let modal = document.querySelector(".modal-account");
    let cancel = document.getElementById("cancel");
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
        modal.classList.toggle("modal-account-show");
        modal.classList.toggle("modal-account");
    })

    edit.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let data_id = element.getAttribute("data-accid");
            let data_username = element.getAttribute("data-username");
            let data_password = element.getAttribute("data-password");
            let data_role = element.getAttribute("data-role");

            let username = document.getElementById("curr_username");
            let password = document.getElementById("curr_password");
            let role = document.getElementById("curr_role");
            let id = document.getElementById("acc-id");
            let role_description;

            if (data_role == 1) {
                role_description = "Admin";
            } else if (data_role == 2) {
                role_description = "Worker";
            } else {
                role_description = "Supplier";
            }

            id.value = data_id;
            username.value = data_username;
            password.value = data_password;
            role.value = data_role;
            role.innerHTML = role_description;

            modal.classList.toggle("modal-account");
            modal.classList.toggle("modal-account-show");
        })
    })

    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
    })

    del.addEventListener("click", () => {
        const deleteacc = document.getElementById("deleteacc");
        deleteacc.submit();
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
        username.value = "";
        password.value = "";
        role.value = "";
        usernameerr.style.display = "none";
        passworderr.style.display = "none";
        roleerr.style.display = "none";
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

        if (event.target.classList == "modal-account-show") {
            modal.classList.toggle("modal-account-show");
            modal.classList.toggle("modal-account");
        }
    })

    closebtn.addEventListener("click", () => {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    })

    add.addEventListener("click", (event) => {

        if (username.value == "" && password.value == "" && role.value == "") {
            event.preventDefault();
            usernameerr.style.display = "block";
            passworderr.style.display = "block";
            roleerr.style.display = "block";
        }

        if (username.value == "") {
            event.preventDefault();
            usernameerr.style.display = "block";
        } else {
            usernameerr.style.display = "none";
        }

        if (password.value == "") {
            event.preventDefault();
            passworderr.style.display = "block";
        } else {
            passworderr.style.display = "none";
        }

        if (role.value == "") {
            event.preventDefault();
            roleerr.style.display = "block";
        } else {
            roleerr.style.display = "none";
        }

    })

    update.addEventListener("click", (event) => {

        let usernameerr = document.getElementById("usernameerror");
        let passworderror = document.getElementById("passworderror");
        let username = document.getElementById("curr_username");
        let password = document.getElementById("curr_password");

        if (username.value == "" && password.value == "" && role.value == "") {
            event.preventDefault();
            usernameerr.style.display = "block";
            passworderror.style.display = "block";
        }

        if (username.value == "") {
            event.preventDefault();
            usernameerr.style.display = "block";
        } else {
            usernameerr.style.display = "none";
        }

        if (password.value == "") {
            event.preventDefault();
            passworderror.style.display = "block";
        } else {
            passworderror.style.display = "none";
        }

    })
    </script>

</html>