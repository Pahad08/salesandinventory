<?php
session_start();
include '../openconn.php';

$number_per_page = 5;
$offset = (1 - 1) * $number_per_page;
$id = 18;

if (empty($_GET['name'])) {
    $sql = "SELECT `transaction`.*, products.name
    FROM `transaction`
    LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
    LEFT JOIN products on `transaction`.`product_id` = products.product_id
    where suppliers.account_id = ?
    LIMIT $number_per_page OFFSET $offset;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["supplier"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $name = "%" . $_GET['name'] . "%";
    $stmt = mysqli_prepare($conn, "SELECT `transaction`.*, products.name
    FROM `transaction`
    LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
    LEFT JOIN products on `transaction`.`product_id` = products.product_id
    where suppliers.account_id = ? and products.name LIKE ?
    LIMIT $number_per_page OFFSET $offset;");
    mysqli_stmt_bind_param($stmt, "is", $_SESSION["supplier"], $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}

?>

<tr id="head">

    <th>Transaction Date</th>
    <th>Delivery Schedule</th>
    <th>Product Name</th>
    <th>Quantity</th>

    <?php while ($row) { ?>
<tr>
    </td>
    <td><?php echo $row['transaction_date']; ?></td>
    <td><?php echo $row['delivery_schedule']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['quantity']; ?></td>

<?php $row = mysqli_fetch_array($result);
    } ?>
</tr>