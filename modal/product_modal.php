<?php

if (isset($_GET['name'])) {
    include '../openconn.php';
} else {
    include 'openconn.php';
}

if (
    !isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])
    && !isset($_SESSION["worker"]) && !isset($_SESSION["worker_username"])
) {
    header("location: ../login.php");
    exit();
}

?>

<div div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Product</h2>
        </div>


        <form action="<?php if (isset($_GET['name'])) {
                            echo "../edit/editproduct.php";
                        } else {
                            echo "edit/editproduct.php";
                        }  ?>" method="post" class="edit-product" id="form">

            <input type="text" id="prod-id" name="id" value="" hidden>

            <div class="input-edit">
                <label for="prod-name">Product Name</label>
                <input type="text" id="prod-name" name="product" value="">
                <p class="empty" id="nameerr">Product cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="prod-kilo">Kilogram</label>
                <input type="number" id="prod-kilo" name="kilogram" value="" inputmode="numeric">
                <p class="empty" id="kiloerr">Product cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="prod-price">Price</label>
                <input type="number" id="prod-price" name="price" value="" inputmode="numeric">
                <p class="empty" id="Priceerr">Product cannot be blank</p>
            </div>

            <div class="buttons">
                <button type="submit" id="update" name="edit" value="edit">Update</button>
                <button id="cancel">Cancel</button>
            </div>
        </form>
    </div>

</div>