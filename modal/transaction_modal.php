<?php
include 'openconn.php';

if (!isset($_SESSION["admin"]) && !isset($_SESSION["admin_username"])) {
    header("location: ../login.php");
    exit();
}

$stmt_suppliers = mysqli_prepare($conn, "SELECT f_name, l_name, supplier_id from suppliers;");
mysqli_stmt_execute($stmt_suppliers);
$result_suppliers = mysqli_stmt_get_result($stmt_suppliers);
$row_suppliers = mysqli_fetch_array($result_suppliers);

$stmt_products = mysqli_prepare($conn, "SELECT `name`, product_id from products;");
mysqli_stmt_execute($stmt_products);
$result_products = mysqli_stmt_get_result($stmt_products);
$row_products = mysqli_fetch_array($result_products);
mysqli_close($conn);

?>

<div class=modal-body>

    <div class="form-modal">

        <div class="header-modal">
            <h2>Edit Supplier</h2>
        </div>

        <form action="edit/edittransaction.php" method="post" class="edit-transaction" id="form">

            <input type="text" value="" name="transaction_id" id="transaction-id" hidden>

            <div class="input-edit">
                <label for="deliver-date">Delivery Date</label>
                <input type="date" id="deliver-date" name="delivery_date">
                <p class="empty" id="delivererr">Delivery Date cannot be blank</p>
            </div>

            <div class=" input-edit">
                <label for="quant">Quantity</label>
                <input type="number" id="quant" name="quantity">
                <p class="empty" id="quanterr">Quantity cannot be blank</p>
            </div>

            <div class="input-edit">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id">
                    <option value="" id="selected-supplier"></option>
                    <?php while ($row_suppliers) { ?>
                        <option value="<?php echo $row_suppliers['supplier_id']; ?>">
                            <?php echo $row_suppliers['f_name'] . " " . $row_suppliers['l_name']; ?>
                        </option>
                    <?php $row_suppliers = mysqli_fetch_array($result_suppliers);
                    } ?>
                </select>
            </div>

            <div class="input-edit">
                <label for="product_id">Products</label>
                <select name="product_id" id="product_id">
                    <option value="" id="selected-product"></option>
                    <?php while ($row_products) { ?>
                        <option value="<?php echo $row_products['product_id']; ?>">
                            <?php echo $row_products['name']; ?>
                        </option>
                    <?php $row_products = mysqli_fetch_array($result_products);
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