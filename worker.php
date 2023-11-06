<?php

session_start();
include 'openconn.php';

if (isset($_SESSION["worker"]) && isset($_SESSION["worker_username"])) {
    $worker_id = $_SESSION["worker"];
} else {
    header("location: login.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT workers.*, accounts.username, accounts.password 
from workers
left join accounts on workers.account_id = accounts.account_id
where accounts.account_id = ?;");
mysqli_stmt_bind_param($stmt, "i", $worker_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);


?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/suppwork.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
        <title>Worker</title>
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
                <h3><?php echo strtoupper($_SESSION["worker_username"]); ?> </h3>
                <a href="logout.php">Logout</a>
            </div>

        </div>


        <div id="nav-body" class="nav">
            <nav id="nav">
                <div id="list-container">

                    <ul class="menu">
                        <p>Account Details</p>
                        <li><a href="worker.php">Account</a></li>
                    </ul>

                    <ul class="menu">
                        <p>Products</p>
                        <li><a href="inventory.php">Inventory</a></li>
                        <li><a href="products.php">Product List</a></li>
                        <li><a href="sales.php">Sales</a></li>
                    </ul>
                </div>

            </nav>
        </div>


        <div class="body">
            <div class="head">
                <h1>Account</h1>
            </div>

            <?php if (isset($_SESSION['updated'])) { ?>
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

            <div class="acc-body">
                <div class="left-side">
                    <img src="images/employer.png" alt="">

                    <div class="about">
                        <p><?php echo $row['f_name'] . " " . $row['l_name']; ?></p>
                        <p>Worker</p>
                    </div>

                </div>

                <div class="right-side">
                    <p>User Information</p>

                    <div class="info">
                        <div class="info-container">
                            <h3>First Name</h3>
                            <p><?php echo $row['f_name']; ?></p>
                        </div>

                        <div class="info-container">
                            <h3>Last Name</h3>
                            <p><?php echo $row['l_name']; ?></p>
                        </div>

                        <div class="info-container">
                            <h3>Contact Number</h3>
                            <p><?php echo $row['contact_number']; ?></p>
                        </div>

                        <div class="info-container">
                            <h3>Username</h3>
                            <p><?php echo $row['username']; ?></p>
                        </div>

                        <div class="edit-buttons">
                            <button id="edit-profile" data-workerid="<?php echo $row['worker_id'] ?>"
                                data-fname="<?php echo $row['f_name'] ?>" data-lname="<?php echo $row['l_name'] ?>"
                                data-contactnumber="<?php echo $row['contact_number'] ?>">Edit Profile</button>
                            <button id="edit-acc" data-accid="<?php echo $row['account_id'] ?>"
                                data-username="<?php echo $row['username'] ?>">Edit Account</button>
                        </div>
                    </div>
                </div>

                <div class="modal-acc">
                    <?php include 'modal/acc_modal.php'; ?>
                </div>

                <div class="modal-profile">
                    <?php include 'modal/profile_modal.php'; ?>
                </div>
            </div>

        </div>

    </body>

    <script src="javascript/admin.js"></script>

    <script>
    let acc = document.querySelector(".modal-acc");
    let profile = document.querySelector(".modal-profile");
    let edit_profile = document.getElementById("edit-profile");
    let edit_acc = document.getElementById("edit-acc");
    let cancel = document.querySelectorAll(".cancel");
    let update_profile = document.querySelector("#update-profile");
    let update_acc = document.querySelector("#update-acc");

    edit_acc.addEventListener("click", () => {

        let data_id = edit_acc.getAttribute("data-accid");
        let data_username = edit_acc.getAttribute("data-username");
        let acc_id = document.getElementById("acc-id");
        let username = document.getElementById("username");

        acc_id.value = data_id;
        username.value = data_username;

        acc.classList.toggle("modal-acc");
        acc.classList.toggle("modal-acc-show");
    })

    edit_profile.addEventListener("click", () => {

        let data_workerid = edit_profile.getAttribute("data-workerid");
        let data_fname = edit_profile.getAttribute("data-fname");
        let data_lname = edit_profile.getAttribute("data-lname");
        let data_number = edit_profile.getAttribute("data-contactnumber");

        let profile_id = document.getElementById("profile-id");
        let fname = document.getElementById("fname");
        let lname = document.getElementById("lname");
        let number = document.getElementById("number");

        profile_id.value = data_workerid;
        fname.value = data_fname;
        lname.value = data_lname;
        number.value = data_number;

        profile.classList.toggle("modal-profile");
        profile.classList.toggle("modal-profile-show");
    })

    cancel.forEach((element) => {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            if (acc.classList.contains("modal-acc-show")) {
                acc.classList.toggle("modal-acc-show");
                acc.classList.toggle("modal-acc");
            }

            if (profile.classList.contains("modal-profile-show")) {
                profile.classList.toggle("modal-profile-show");
                profile.classList.toggle("modal-profile");
            }

        })
    })


    window.addEventListener("click", (event) => {
        if (event.target.className == "modal-acc-show" && acc.classList.contains("modal-acc-show")) {
            acc.classList.toggle("modal-acc-show");
            acc.classList.toggle("modal-acc");
        }

        if (event.target.className == "modal-profile-show" && profile.classList.contains(
                "modal-profile-show")) {
            profile.classList.toggle("modal-profile-show");
            profile.classList.toggle("modal-profile");
        }
    })

    update_acc.addEventListener("click", (event) => {
        let usernameerr = document.getElementById("usernameerr");
        let passworderror = document.getElementById("passworderr");
        let username = document.getElementById("username");
        let password = document.getElementById("password");

        if (username.value == "" && password.value == "") {
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

    update_profile.addEventListener("click", (event) => {
        let fnameerr = document.getElementById("fnameerr");
        let lnameerr = document.getElementById("lnameerr");
        let numbererr = document.getElementById("numbererr");
        let fname = document.getElementById("fname");
        let lname = document.getElementById("lname");
        let number = document.getElementById("number");

        if (fname.value == "" && lname.value == "" && number.value == "") {
            event.preventDefault();
            fnameerr.style.display = "block";
            numbererr.style.display = "block";
            lnameerr.style.display = "block";
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
            numbererr.innerHTML = "Contact Number cannot be blank";
            numbererr.style.display = "block";
        } else if (number.value.length < 11) {
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

    if (document.querySelector(".updated")) {
        document.querySelector(".updated").addEventListener("animationend", () => {
            document.querySelector(".updated").style.display = "none";
        })

        document.querySelector(".updated").addEventListener("click", () => {
            document.querySelector(".updated").style.display = "none";
        })
    } else if (document.querySelector(".exist")) {
        document.querySelector(".exist").addEventListener("animationend", () => {
            document.querySelector(".exist").style.display = "none";
        })

        document.querySelector(".exist").addEventListener("click", () => {
            document.querySelector(".exist").style.display = "none";
        })
    }
    </script>

</html>