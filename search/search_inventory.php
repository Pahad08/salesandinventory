<?php
include '../openconn.php';

$number_per_page = 5;
$offset = (1 - 1) * $number_per_page;

if (empty($_GET['name'])) {
    $sql = "SELECT products.name, stocks.stock_id, stocks.quantities, stocks.stock_in, stocks.stock_out
    from stocks
    join products on stocks.product_id = products.product_id LIMIT $number_per_page OFFSET $offset;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $name = "%" . $_GET['name'] . "%";
    $sql = "SELECT products.name, stocks.stock_id, stocks.quantities, stocks.stock_in, stocks.stock_out
    from stocks
    join products on stocks.product_id = products.product_id where products.name LIKE ? 
    LIMIT $number_per_page OFFSET $offset;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}

?>

<tr id="head">
    <th></th>
    <th>Product Name</th>
    <th>Quantities</th>
    <th>Stock In</th>
    <th>Stock Out</th>
</tr>
<?php while ($row) { ?>
    <tr>
        <td></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['quantities']; ?></td>
        <td><?php echo $row['stock_in']; ?></td>
        <td><?php echo $row['stock_out']; ?></td>
    </tr>

<?php $row = mysqli_fetch_array($result);
} ?>