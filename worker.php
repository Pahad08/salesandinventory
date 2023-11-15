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
        <link rel="shortcut icon" href="images/logo.jpg" type="image/x-icon">
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

                <?php if (isset($_SESSION['updated'])) { ?>
                <div class="updated">
                    <p><?php echo $_SESSION['updated']; ?></p>
                </div>
                <?php unset($_SESSION['updated']);
            } else if (isset($_SESSION['exist'])) { ?>
                <div class="exist">
                    <p><?php echo $_SESSION['exist']; ?></p>
                </div>
                <?php unset($_SESSION['exist']);
            } else if (isset($_SESSION['err-pass'])) { ?>
                <div class="pass-err">
                    <p><?php echo $_SESSION['err-pass']; ?></p>
                </div>
                <?php unset($_SESSION['err-pass']);
            } ?>
            </div>



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

    <script src="javascript/navigation.js"></script>

    <script src="javascript/worker.js"></script>

</html>