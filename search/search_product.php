<?php

include '../openconn.php';

if (!empty($_GET['name'])) {
    $name = "%" . $_GET['name'] . "%";
    $sql = "SELECT * from products where `name` LIKE ? LIMIT 5;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $sql = "SELECT * from products LIMIT 5;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}

mysqli_close($conn);

echo " <tr>
<th></th>
<th>Product Name</th>
<th>Kilogram</th>
<th>Price</th>
<th>Edit</th>
</tr>";

while ($row) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='product_id[]' value=" . $row['product_id'] . " class='checkbox'></td>";
    echo " <td>" . $row['name'] . "</td>
<td>" .  $row['kilogram'] . "</td>
<td>" . $row['price'] . "</td>";
    echo "<td id='action'> <button class='edit' data-productid=" . $row['product_id'] .
        " data-name=" . $row['name'] . " data-kilogram=" . $row['kilogram'] .
        " data-price=" .   $row['price'] . "><img src='images/edit.png'>Edit</button>
</td>";
    echo "</tr>";
    $row = mysqli_fetch_array($result);
}
