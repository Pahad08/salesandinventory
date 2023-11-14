<?php

include '../openconn.php';

if (!empty($_GET['name'])) {
    $name = "%" . $_GET['name'] . "%";
    $sql = "SELECT sales.sale_id,sales.product_id ,sales.sale_date, products.name, sales.quantity * products.price as sale, sales.quantity
    from sales join products on sales.product_id = products.product_id
    where products.name like ?
    order by sales.sale_id DESC
    LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $sql = "SELECT sales.sale_id,sales.product_id ,sales.sale_date, products.name, sales.quantity * products.price as sale, sales.quantity
    from sales join products on sales.product_id = products.product_id
    order by sales.sale_id DESC
    LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}

mysqli_close($conn);
echo " <tr>
<th></th>
<th>Product Name</th>
<th>Date</th>
<th>Quantity</th>
<th>Income</th>
<th>Edit</th>
</tr>";

while ($row) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='sale_id[]' value=" . $row['sale_id'] . " class='checkbox'></td>";
    echo " <td>" . $row['name'] . "</td>
<td>" .  $row['sale_date'] . "</td>
<td>" . $row['quantity'] . "</td>
<td>" . $row['sale'] . "</td>";
    echo "<td id='action'> <button class='edit' data-id=" . $row['sale_id'] .
        " data-quantity=" . $row['quantity'] . " data-currquantity=" . $row['quantity'] .
        " data-prodid=" .   $row['product_id'] . " data-prodname=" . $row['name'] .
        "><img src='images/edit.png'>Edit</button>
</td>";
    echo "</tr>";
    $row = mysqli_fetch_array($result);
}
