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

$sql = "SELECT * from expenses LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(expense_id) as total from expenses;";
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
    <title>Expenses</title>
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
                    <li><a href="">Expenses</a></li>
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
                        <h2>Add Expense</h2>
                        <p id="closebtn">&#10006;</p>
                    </div>

                    <form action="add/addexpense.php" method="post" id="form-body">

                        <div class="input-body">
                            <label for="description">Description</label>
                            <input type="text" id="description" name="description">
                            <p class="emptyinput" id="descriptionerr">Description cannot be blank</p>
                        </div>

                        <div class="input-body">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" name="amount">
                            <p class="emptyinput" id="amounterr">Amount cannot be blank</p>
                        </div>

                        <div class="input-body">
                            <label for="expense-date">Date</label>
                            <input type="date" id="expense-date" name="expense-date">
                            <p class="emptyinput" id="expense-dateerr">Date cannot be blank</p>
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
                        <h2>Expenses</h2>

                        <div class="btns">
                            <button id="expenseadd" class="add"><img src="images/add.png" alt="">Add
                                Expense</button>
                            <button id="delete"><img src="images/delete.png">Delete</button>
                            <button id="selectall"><img src="images/selectall.png" alt="">Select All</button>
                        </div>
                    </div>

                    <div class="search">
                        <form action="search/search_expense.php" method="get">
                            <input type="text" id="search" placeholder="Search" name="description">
                            <button type="submit" id="searchbtn">Search</button>
                        </form>
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
                }
                ?>

                <table id="table">
                    <tr id="head">
                        <th></th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <form action="delete/deleteexpense.php" id="deleteexpense" method="post">
                        <?php while ($row) { ?>
                        <tr>
                            <td><input type="checkbox" name="expense_id[]" value="<?php echo $row['expense_id']; ?>"
                                    class="checkbox"></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['expense_date']; ?></td>
                            <td id="action"> <button class="edit" data-expenseid="<?php echo $row['expense_id']; ?>"
                                    data-description="<?php echo $row['description']; ?>"
                                    data-amount="<?php echo $row['amount']; ?>"
                                    data-expense_date="<?php echo $row['expense_date']; ?>"><img src="images/edit.png"
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
                                <p>Are you sure you want to delete all selected items?</p>
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
                                    echo "href=expense.php?page_number=" . $previouspage;
                                } ?>>&laquo;</a></li>

                        <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                        <li><a href="<?php echo "expense.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
                        </li>
                        <?php } ?>


                        <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                    echo "href=expense.php?page_number=" . $nextpage;
                                } ?>>&raquo;</a></li>
                    </ul>

                </div>

                <div class="modal-expense">
                    <?php include 'modal/expense_modal.php'; ?>
                </div>

            </div>


        </div>
</body>

<script src="javascript/admin.js"></script>
<script>
let form = document.getElementById("form");
let openform = document.getElementById("expenseadd");
let closebtn = document.getElementById("closebtn");
let reset = document.getElementById("reset");
let deletebtn = document.querySelector("#delete");
let canceldelete = document.getElementById("close-deletion");
let alertbody = document.getElementById("alert-body");
let add = document.getElementById("add");
let description = document.getElementById("description");
let amount = document.getElementById("amount");
let expense_date = document.getElementById("expense-date");
let descriptionerr = document.getElementById("descriptionerr");
let amounterr = document.getElementById("amounterr");
let expense_dateerr = document.getElementById("expense-dateerr");
let del = document.getElementById("del");
const edit = document.querySelectorAll(".edit");
let modal = document.querySelector(".modal-expense");
let cancel = document.getElementById("cancel");
let update = document.getElementById("update");
let selectall = document.getElementById('selectall');
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
    modal.classList.toggle("modal-expense-show");
    modal.classList.toggle("modal-expense");
})

edit.forEach((element) => {
    element.addEventListener("click", () => {
        event.preventDefault();
        let data_id = element.getAttribute("data-expenseid");
        let description = element.getAttribute("data-description");
        let amount = element.getAttribute("data-amount");
        let date = element.getAttribute("data-expense_date");
        let expense_date = document.getElementById("expensedate");
        let exp_description = document.getElementById("expense-description");
        let expense_amount = document.getElementById("expense-amount");
        let expense_id = document.getElementById("expense-id");

        expense_id.value = data_id;
        exp_description.value = description;
        expense_amount.value = amount;
        expense_date.value = date;

        modal.classList.toggle("modal-expense");
        modal.classList.toggle("modal-expense-show");
    })
})

deletebtn.addEventListener("click", (event) => {
    event.preventDefault();
    alertbody.classList.toggle("alert-body");
    alertbody.classList.toggle("alert-body-show");
})

del.addEventListener("click", () => {
    const deleteexpense = document.getElementById("deleteexpense");
    deleteexpense.submit();
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
    description.value = "";
    amount.value = "";
    expense_date.value = "";
    descriptionerr.style.display = "none";
    amounterr.style.display = "none";
    expense_dateerr.style.display = "none";
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

    if (event.target.classList == "modal-expense-show") {
        modal.classList.toggle("modal-expense-show");
        modal.classList.toggle("modal-expense");
    }
})

closebtn.addEventListener("click", () => {
    form.classList.toggle("show-form");
    form.classList.toggle("form");
})

add.addEventListener("click", (event) => {

    if (description.value == "" && amount.value == "" && expense_date.value == "") {
        event.preventDefault();
        descriptionerr.style.display = "block";
        amounterr.style.display = "block";
        expense_dateerr.style.display = "block";
    }

    if (description.value == "") {
        event.preventDefault();
        descriptionerr.style.display = "block";
    } else {
        descriptionerr.style.display = "none";
    }

    if (amount.value == "") {
        event.preventDefault();
        amounterr.style.display = "block";
    } else {
        amounterr.style.display = "none";
    }

    if (expense_date.value == "") {
        event.preventDefault();
        expense_dateerr.style.display = "block";
    } else {
        expense_dateerr.style.display = "none";
    }

})

update.addEventListener("click", (event) => {

    let proderr = document.getElementById("nameerr");
    let kilogramerr = document.getElementById("kiloerr");
    let priceerr = document.getElementById("Priceerr");
    let name = document.getElementById("prod-name");
    let kilogram = document.getElementById("prod-kilo");
    let price = document.getElementById("prod-price");

    if (name.value == "" && kilogram.value == "" && price.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
        kilogramerr.style.display = "block";
        kilogramerr.style.display = "block";
    }

    if (name.value == "") {
        event.preventDefault();
        proderr.style.display = "block";
    } else {
        proderr.style.display = "none";
    }

    if (kilogram.value == "") {
        event.preventDefault();
        kilogramerr.style.display = "block";
    } else {
        kilogramerr.style.display = "none";
    }

    if (price.value == "") {
        event.preventDefault();
        priceerr.style.display = "block";
    } else {
        priceerr.style.display = "none";
    }

})
</script>

</html>