<?php
include 'openconn.php';

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {
    $admin_id = $_SESSION["admin"];
} else {
    header("location: login.php");
    exit();
}

$prod_sql = "SELECT product_id, `name` from products;";
$stmt_prod = mysqli_prepare($conn, $prod_sql);
mysqli_stmt_execute($stmt_prod);
$result_prod = mysqli_stmt_get_result($stmt_prod);
$row_prod = mysqli_fetch_array($result_prod);
mysqli_close($conn);

?>

<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Sale</h2>
        </div>


        <form action="edit/editsales.php" method="post" class="edit-sales" id="form">

            <input type="number" value="" name="saleid" id="sale-id" hidden>

            <input type="number" value="" name="curr_quantity" id="curr-quantity" hidden>

            <div class="input-edit">
                <label for="prodselect">Product Name</label>
                <select name="prodid" id="prodselect">
                    <option value="" id="selected"></option>
                    <?php while ($row_prod) { ?>
                    <option value="<?php echo $row_prod['product_id']; ?>">
                        <?php echo $row_prod['name']; ?></option>
                    <?php $row_prod = mysqli_fetch_array($result_prod);
                    } ?>
                </select>
                <p class="empty" id="iderr">Product cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="date-value">Date</label>
                <input type="date" id="date-value" name="date" value="">
                <p class="empty" id="dateerror">Date cannot be blank</p>
            </div>

            <div class=" input-edit">
                <label for="quantity-value">Quantity</label>
                <input type="number" id="quantity-value" name="quantity" value="">
                <p class="empty" id="quanterr">Quantity cannot be blank</p>
            </div>

            <div class="buttons">
                <button type="submit" id="update" name="edit" value="edit">Update</button>
                <button id="cancel">Cancel</button>
            </div>
        </form>


        <?php while ($row_prod) {
            echo $row_prod['name'];
            $row_prod = mysqli_fetch_array($result_prod);
        } ?>
    </div>

</div>