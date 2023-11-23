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

?>

<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Supplier</h2>
        </div>

        <form action="<?php if (isset($_GET['name'])) {
                            echo "../edit/edit_supplier.php";
                        } else {
                            echo "edit/edit_supplier.php";
                        }  ?>" method="post" class="edit-supplier" id="form">

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
                <label for="supplier-number">Contact Number</label>
                <input type="number" id="supplier-number" name="number" inputmode="numeric">
                <p class="empty" id="numerr"></p>
            </div>

            <div class=" input-edit">
                <label for="supplier-company">Company</label>
                <input type="text" id="supplier-company" name="company">
                <p class="empty" id="companyerror">Company cannot be blank</p>
            </div>

            <div class="buttons" id="btn-supplier">
                <button type="submit" id="update" name="edit" value="edit">Update</button>
                <button id="cancel">Cancel</button>
            </div>
        </form>

    </div>

</div>