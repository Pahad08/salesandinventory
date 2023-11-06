<?php
include 'openconn.php';

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
            <h2>Edit Product</h2>
        </div>


        <form action="edit/edit_acc.php" method="post" class="edit-account" id="form">

            <input type="text" id="acc-id" name="id" value="" hidden>

            <div class="input-edit">
                <label for="curr_username">Username</label>
                <input type="text" id="curr_username" name="username">
                <p class="empty" id="usernameerror">Username cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="curr_password">Password</label>
                <input type="password" id="curr_password" name="password">
                <p class="empty" id="passworderror">Password cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="role">Role</label>
                <select name="role">
                    <option value="" id="curr_role"></option>
                    <option value="1">Admin</option>
                    <option value="2">Worker</option>
                    <option value="3">Supplier</option>
                </select>
            </div>

            <div class="buttons">
                <button type="submit" id="update" name="edit" value="edit">Update</button>
                <button id="cancel">Cancel</button>
            </div>
        </form>
    </div>

</div>