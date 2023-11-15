<?php
if (isset($_GET['description'])) {
    include '../openconn.php';
} else {
    include 'openconn.php';
}

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

?>

<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Product</h2>
        </div>


        <form action="
        
        <?php if (isset($_GET['description'])) {
            echo "../edit/editexpense.php";
        } else {
            echo "edit/editexpense.php";
        }  ?>" method="post" class="edit-expense" id="form">

            <input type="text" id="expense-id" name="id" value="" hidden>

            <div class="input-edit">
                <label for="expense-description">Description</label>
                <input type="text" id="expense-description" name="description" value="">
                <p class="empty" id="desc-err">Product cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="expense-amount">Amount</label>
                <input type="number" id="expense-amount" name="amount" value="" inputmode="numeric">
                <p class="empty" id="amount-err">Product cannot be blank</p>
            </div>

            <div class="buttons">
                <button type="submit" id="update" name="edit" value="edit">Update</button>
                <button id="cancel">Cancel</button>
            </div>
        </form>
    </div>

</div>