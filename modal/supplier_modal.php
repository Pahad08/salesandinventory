<?php
include 'openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: login.php");
    exit();
}

$acc_sql = "SELECT accounts.username, accounts.account_id
FROM accounts
left join suppliers on accounts.account_id = suppliers.account_id
left join workers on accounts.account_id = workers.account_id
left join admins on accounts.account_id = admins.account_id
where suppliers.account_id is null and admins.account_id is null and workers.account_id is null;";
$stmt_acc = mysqli_prepare($conn, $acc_sql);
mysqli_stmt_execute($stmt_acc);
$result_acc = mysqli_stmt_get_result($stmt_acc);
$row_acc = mysqli_fetch_array($result_acc);
mysqli_close($conn);

?>

<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Supplier</h2>
        </div>

        <form action="edit/edit_supplier.php" method="post" class="edit-supplier" id="form">

            <input type="text" value="" name="supplier_id" id="supplier-id" hidden>

            <div class="input-edit">
                <label for="supplier-fname">First Name</label>
                <input type="text" id="supplier-fname" name="fname">
                <p class="empty" id="fnameerror">First Name cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="supplier-lname">Last Name</label>
                <input type="text" id="supplier-lname" name="lname">
                <p class="empty" id="lnameerror">Last Name cannot be blank</p>
            </div>

            <div class=" input-edit">
                <label for="supplier-numbe">Contact Number</label>
                <input type="number" id="supplier-number" name="number">
                <p class="empty" id="numerr">Contact Number cannot be blank</p>
            </div>

            <div class=" input-edit">
                <label for="supplier-company">Company</label>
                <input type="text" id="supplier-company" name="company">
                <p class="empty" id="companyerror">Company cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="supplier-accountid">Account ID</label>
                <select name="account-id" id="supplier-accountid">
                    <option value="" id="selected"></option>
                    <?php while ($row_acc) { ?>
                    <option value="<?php echo $row_acc['account_id']; ?>">
                        <?php echo $row_acc['username']; ?></option>
                    <?php $row_acc = mysqli_fetch_array($result_acc);
                    } ?>
                </select>

            </div>

            <div class="buttons" id="btn-supplier">
                <button type="submit" id="update" name="edit" value="edit">Update</button>
                <button id="cancel">Cancel</button>
            </div>
        </form>

    </div>

</div>