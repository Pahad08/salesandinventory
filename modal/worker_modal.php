<?php
if (isset($_GET['name'])) {
    include '../openconn.php';
} else {
    include 'openconn.php';
}

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: ../login.php");
    exit();
}

$acc_sql = "SELECT accounts.username, accounts.account_id
FROM accounts
left join suppliers on accounts.account_id = suppliers.account_id
left join workers on accounts.account_id = workers.account_id
where suppliers.account_id is null and workers.account_id is null
and not accounts.username = 'admin' and not accounts.role = 3 and not accounts.role = 1;";
$stmt_acc = mysqli_prepare($conn, $acc_sql);
mysqli_stmt_execute($stmt_acc);
$result_acc = mysqli_stmt_get_result($stmt_acc);
$row_acc = mysqli_fetch_array($result_acc);
mysqli_close($conn);

?>

<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Worker</h2>
        </div>

        <form action="<?php if (isset($_GET['name'])) {
                            echo "../edit/edit_worker.php";
                        } else {
                            echo "edit/edit_worker.php";
                        }  ?>" method="post" class="edit-worker" id="form">

            <input type="text" value="" name="worker_id" id="worker-id" hidden>

            <div class="input-edit">
                <label for="worker-fname">First Name</label>
                <input type="text" id="worker-fname" name="fname">
                <p class="empty" id="fnameerror">First Name cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="worker-lname">Last Name</label>
                <input type="text" id="worker-lname" name="lname">
                <p class="empty" id="lnameerror">Last Name cannot be blank</p>
            </div>

            <div class=" input-edit">
                <label for="worker-number">Contact Number</label>
                <input type="number" id="worker-number" name="number" inputmode="numeric">
                <p class="empty" id="numerr"></p>
            </div>

            <div class="input-edit">
                <label for="worker-accountid">Account</label>
                <select name="account-id" id="worker-accountid">
                    <option value="" id="selected"></option>
                    <?php while ($row_acc) { ?>
                        <option value="<?php echo $row_acc['account_id']; ?>">
                            <?php echo $row_acc['username']; ?></option>
                    <?php $row_acc = mysqli_fetch_array($result_acc);
                    } ?>
                </select>

            </div>

            <div class="buttons" id="btn-worker">
                <button type="submit" id="update" name="edit" value="edit">Update</button>
                <button id="cancel">Cancel</button>
            </div>
        </form>

    </div>

</div>