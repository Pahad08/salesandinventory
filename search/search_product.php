<?php

session_start();
include '../openconn.php';

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../login.php");
    exit();
}

if (isset($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
} else {
    $page_number = 1;
}

$product_name = "%" . $_GET['name'] . "%";
$number_per_page = 5;
$offset = ($page_number - 1) * $number_per_page;
$nextpage = $page_number + 1;
$previouspage = $page_number - 1;

$sql = "SELECT * from products where `name` LIKE ? LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $product_name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(product_id) as total from products;";
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
        <link rel="stylesheet" href="../css/admin.css">
        <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
        <title>Products</title>
    </head>

    <body>

        <div class="head"></div>

        <div class="header">

            <div class="left">

                <div id="menu-icon">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>

                <img src="../images/logo.png" alt="logo">
                <h2> Badong Lechon Manok</h2>
            </div>

            <div class="right">
                <h3><?php echo $username = (isset($_SESSION["admin_username"])) ?
                    strtoupper($_SESSION["admin_username"]) : strtoupper($_SESSION["worker_username"]); ?>
                </h3>
                <a href="../logout.php">Logout</a>
            </div>

        </div>


        <div id="nav-body" class="nav">
            <nav id="nav">
                <div id="list-container">

                    <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                    <ul class="menu">
                        <p>Data Dashboard</p>
                        <li><a href="../admin.php">Dashboard</a></li>
                    </ul>
                    <?php } else { ?>
                    <ul class="menu">
                        <p>Account Details</p>
                        <li><a href="../worker.php">Account</a></li>
                    </ul>
                    <?php } ?>

                    <?php if (
                    isset($_SESSION["admin"]) || isset($_SESSION["admin_username"])
                    || isset($_SESSION["worker"]) || isset($_SESSION["worker_username"])
                ) { ?>
                    <ul class="menu">
                        <p>Products</p>
                        <li><a href="../inventory.php">Inventory</a></li>
                        <li><a href="../products.php">Product List</a></li>
                        <li><a href="../sales.php">Sales</a></li>
                        <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                        <li><a href="../expense.php">Expenses</a></li>
                        <?php } ?>
                    </ul>
                    <?php } ?>

                    <?php if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) { ?>
                    <ul class="menu">
                        <p>Suppliers/Workers</p>
                        <li><a href="../supplier_list.php">List of Suppliers</a></li>
                        <li><a href="../workers_list.php">List of Workers</a></li>
                        <li><a href="../schedules.php">Schedule of Deliveries</a></li>
                    </ul>

                    <ul class="menu">
                        <p>Users</p>
                        <li><a href="../users.php">Users List</a></li>
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
                            <h2>Add Product</h2>
                            <p id="closebtn">&#10006;</p>
                        </div>

                        <form action="../add/addproduct.php" method="post" id="form-body">

                            <div class="input-body">
                                <label for="product-name">Product Name</label>
                                <input type="text" id="product-name" name="product">
                                <p class="emptyinput" id="proderr">Product cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="kilogram">Kilogram</label>
                                <input type="number" id="kilogram" name="kilogram">
                                <p class="emptyinput" id="kilogramerr">Kilogram cannot be blank</p>
                            </div>

                            <div class="input-body">
                                <label for="price">Price</label>
                                <input type="number" id="price" name="price">
                                <p class="emptyinput" id="priceerr">Price cannot be blank</p>
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
                            <h2>Products</h2>

                            <div class="btns">
                                <button id="prodadd" class="add"><img src="../images/add.png" alt="">Add
                                    Product</button>
                                <button id="delete"><img src="../images/delete.png">Delete</button>
                                <button id="selectall"><img src="../images/selectall.png" alt="">Select All</button>
                            </div>
                        </div>

                        <div class="search">
                            <form action="search_product.php" method="GET">
                                <input type="text" id="search" placeholder="Search" name="name">
                                <button id="searchbtn" type="submit">Search</button>
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
                } else if (isset($_SESSION['exist'])) { ?>
                    <div class="exist">
                        <p><span>&#10003;</span> <?php echo $_SESSION['exist']; ?></p>
                    </div>
                    <?php unset($_SESSION['exist']);
                } ?>

                    <table id="table">
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Kilogram</th>
                            <th>Price</th>
                            <th>Edit</th>
                        </tr>
                        <form action="../delete/deleteproduct.php" id="deleteproduct" method="post">
                            <?php while ($row) { ?>
                            <tr>
                                <td><input type="checkbox" name="product_id[]" value="<?php echo $row['product_id']; ?>"
                                        class="checkbox"></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['kilogram']; ?></td>
                                <td><?php echo $row['price']; ?></td>
                                <td id="action"> <button class="edit" data-productid="<?php echo $row['product_id']; ?>"
                                        data-name="<?php echo $row['name']; ?>"
                                        data-kilogram="<?php echo $row['kilogram']; ?>"
                                        data-price="<?php echo $row['price']; ?>"><img src="../images/edit.png"
                                            alt="">Edit</button>
                                </td>

                                <?php $row = mysqli_fetch_array($result);
                        } ?>
                            </tr>
                        </form>
                        <div class="alert-body" id="alert-body">
                            <div class="alert-container">
                                <img src="../images/warning.png">
                                <div class="text-warning">
                                    <p>Are you sure you want to delete?<br>(Stocks, sales and transactions will also be
                                        deleted)</p>
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
                                    echo "href=../product.php?page_number=" . $previouspage;
                                } ?>>&laquo;</a></li>

                            <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                            <li><a
                                    href="<?php echo "../products.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
                            </li>
                            <?php } ?>


                            <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                    echo "href=../products.php?page_number=" . $nextpage;
                                } ?>>&raquo;</a></li>
                        </ul>

                    </div>

                </div>

                <div class="modal-product">
                    <?php include '../modal/product_modal.php'; ?>
                </div>

            </div>


        </div>
    </body>

    <script src="../javascript/admin.js"></script>
    <script>
    let form = document.getElementById("form");
    let openform = document.getElementById("prodadd");
    let closebtn = document.getElementById("closebtn");
    let reset = document.getElementById("reset");
    let deletebtn = document.querySelector("#delete");
    let canceldelete = document.getElementById("close-deletion");
    let alertbody = document.getElementById("alert-body");
    let add = document.getElementById("add");
    let name = document.getElementById("product-name");
    let kilogram = document.getElementById("kilogram");
    let price = document.getElementById("price");
    let proderr = document.getElementById("proderr");
    let kilogramerr = document.getElementById("kilogramerr");
    let priceerr = document.getElementById("priceerr");
    let del = document.getElementById("del");
    const edit = document.querySelectorAll(".edit");
    let modal = document.querySelector(".modal-product");
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
        modal.classList.toggle("modal-product-show");
        modal.classList.toggle("modal-product");
    })

    edit.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let data_id = element.getAttribute("data-productid");
            let data_name = element.getAttribute("data-name");
            let data_kilogram = element.getAttribute("data-kilogram");
            let data_price = element.getAttribute("data-price");
            let name = document.getElementById("prod-name");
            let kilogram = document.getElementById("prod-kilo");
            let price = document.getElementById("prod-price");
            let id = document.getElementById("prod-id");

            id.value = data_id;
            name.value = data_name;
            kilogram.value = data_kilogram;
            price.value = data_price;

            modal.classList.toggle("modal-product");
            modal.classList.toggle("modal-product-show");
        })
    })

    deletebtn.addEventListener("click", (event) => {
        event.preventDefault();
        alertbody.classList.toggle("alert-body");
        alertbody.classList.toggle("alert-body-show");
    })

    del.addEventListener("click", () => {
        const deleteprod = document.getElementById("deleteproduct");
        deleteprod.submit();
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
        name.value = "";
        kilogram.value = "";
        price.value = "";
        proderr.style.display = "none";
        kilogramerr.style.display = "none";
        priceerr.style.display = "none";
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

        if (event.target.classList == "modal-product-show") {
            modal.classList.toggle("modal-product-show");
            modal.classList.toggle("modal-product");
        }
    })

    closebtn.addEventListener("click", () => {
        form.classList.toggle("show-form");
        form.classList.toggle("form");
    })

    add.addEventListener("click", (event) => {

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
            priceerr.style.display = "block";
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