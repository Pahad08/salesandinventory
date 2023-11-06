<?php
session_start();
include '../openconn.php';

if (!empty($_GET['description'])) {
    $description = "%" . $_GET['description'] . "%";
    $sql = "SELECT * from expenses where `description` like ? LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $description);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $sql = "SELECT * from expenses LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}

mysqli_close($conn);

echo " <tr>
<th></th>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>
</tr>";

while ($row) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='expense_id[]' value=" . $row['expense_id'] . " class='checkbox'></td>";
    echo " <td>" . $row['description'] . "</td>
<td>" .  $row['amount'] . "</td>
<td>" . $row['expense_date'] . "</td>";
    echo "<td id='action'> <button class='edit' data-expenseid=" . $row['expense_id'] .
        " data-description=" . $row['description'] . " data-amount=" . $row['amount'] .
        "><img src='images/edit.png'>Edit</button>
</td>";
    echo "</tr>";
    $row = mysqli_fetch_array($result);
}