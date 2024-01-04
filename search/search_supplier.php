<?php

include '../openconn.php';

if (!empty($_GET['name'])) {
    $name = "%" . $_GET['name'] . "%";
    $sql = "SELECT suppliers.*, accounts.username
    FROM suppliers
    LEFT join accounts on suppliers.account_id = accounts.account_id
    where suppliers.f_name LIKE ? or suppliers.l_name LIKE ?
    LIMIT 5;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $name, $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $sql = "SELECT suppliers.*, accounts.username
    FROM suppliers
    LEFT join accounts on suppliers.account_id = accounts.account_id
    LIMIT 5;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}

mysqli_close($conn);

echo " <tr>
<th></th>
<th>Name</th>
<th>Contact Number</th>
<th>Company Name</th>
<th>Account</th>
<th>Edit</th>
</tr>";

while ($row) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='account_id[]' value=" . $row['account_id'] . " class='checkbox'></td>";
    echo " <td>" . $row['f_name'] . " " . $row['l_name'] . "</td>
<td>" .  $row['contact_number'] . "</td>
<td>" . $row['company_name'] . "</td>
<td>" . $row['username'] . "</td>";
    echo "<td id='action'> <button class='edit' data-supplierid=" . $row['supplier_id'] .
        " data-fname=" . $row['f_name'] . " data-lname=" . $row['l_name'] .
        " data-number= " . $row['contact_number'] . " data-company=" . $row['company_name'] .
        "><img src='images/edit.png'>Edit</button>
</td>";
    echo "</tr>";
    $row = mysqli_fetch_array($result);
}