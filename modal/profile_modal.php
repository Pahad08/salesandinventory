<?php
include 'openconn.php';

if (
    !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
    && !isset($_SESSION["supplier"]) && !isset($_SESSION["supplier_username"])
) {
    header("location: ../index.php");
    exit();
}

?>

<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Profile Details</h2>
        </div>

        <form action="edit/edit_profile.php" method="post" class="edit-profile" class="form">

            <input type="text" id="profile-id" name="id" value="" hidden>

            <div class="input-edit">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="">
                <p class="empty" id="fnameerr">First Name cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="">
                <p class="empty" id="lnameerr">Last Name cannot be blank</p>
            </div>

            <?php if (isset($_SESSION["supplier"]) && isset($_SESSION["supplier_username"])) { ?>
                <div class="input-edit">
                    <label for="company">Company Name</label>
                    <input type="text" id="company" name="company" value="">
                    <p class="empty" id="companyerr">Company Name cannot be blank</p>
                </div>
            <?php } ?>

            <div class="input-edit">
                <label for="number">Contact Number</label>
                <input type="number" id="number" name="number" value="" inputmode="numeric">
                <p class="empty" id="numbererr"></p>
            </div>

            <div class="buttons">
                <button type="submit" id="update-profile" class="update" name="edit" value="edit">Update</button>
                <button class="cancel">Cancel</button>
            </div>
        </form>
    </div>



</div>