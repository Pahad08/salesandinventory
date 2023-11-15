<?php
include 'openconn.php';

if (
    !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
    && !isset($_SESSION["supplier"]) && !isset($_SESSION["supplier_username"])
) {
    header("location: ../login.php");
    exit();
}

?>


<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Account Details</h2>
        </div>

        <form action="edit/edit_acc.php" method="post" class="edit-acc" class="form">

            <input type="text" id="acc-id" name="id" value="" hidden>

            <div class="input-edit">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="">
                <p class="empty" id="usernameerr">Username cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="password">Current Password</label>
                <input type="password" id="curr-pass" name="curr-pass" value="">
                <p class="empty" id="curr-passworderr">Current Password cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="password">New Password</label>
                <input type="password" id="new-pass" name="new-pass" value="">
                <p class="empty" id="new-passworderr">New Password cannot be blank</p>
            </div>

            <div class="buttons">
                <button type="submit" id="update-acc" class="update" name="edit" value="edit">Update</button>
                <button class="cancel">Cancel</button>
            </div>
        </form>
    </div>

</div>