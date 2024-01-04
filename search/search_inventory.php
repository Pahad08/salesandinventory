<?php
include '../openconn.php';
session_start();

if (empty($_GET['name'])) {
    $sql = "SELECT products.name, stocks.stock_id, stocks.quantities, stocks.stock_in, stocks.stock_out
    from stocks
    join products on stocks.product_id = products.product_id LIMIT 5;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $name = "%" . $_GET['name'] . "%";
    $sql = "SELECT products.name, stocks.stock_id, stocks.quantities, stocks.stock_in, stocks.stock_out
    from stocks
    join products on stocks.product_id = products.product_id where products.name LIKE ? 
    LIMIT 5;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}
mysqli_close($conn);
echo "<tr>";
if (
    isset($_SESSION['admin']) || isset($_SESSION['admin_username']) ||
    isset($_SESSION['worker']) || isset($_SESSION['worker_username'])
) {
    echo "<th></th>";
}
echo " <th>Product Name</th>
<th>Quantities</th>
<th>Stock In</th>
<th>Stock Out</th>
</tr>";

while ($row) {
    echo "<tr>";
    if (
        isset($_SESSION['admin']) || isset($_SESSION['admin_username']) ||
        isset($_SESSION['worker']) || isset($_SESSION['worker_username'])
    ) {
        echo "<td><input type='checkbox' name='stock_id[]' value=" . $row['stock_id'] . " class='checkbox'></td>";
    }
    echo " <td>" . $row['name'] . "</td>
<td>" .  $row['quantities'] . "</td>
<td>" . $row['stock_in'] . "</td>
<td>" . $row['stock_out'] . "</td>";;
    echo "</tr>";
    $row = mysqli_fetch_array($result);
}