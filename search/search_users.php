<?php

include '../openconn.php';

if (!empty($_GET['username'])) {
    $username = "%" . $_GET['username'] . "%";
    $sql = "SELECT * from accounts where username LIKE ? LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $sql = "SELECT * from accounts LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}
mysqli_close($conn);

echo "  <tr >
<th></th>
<th>Username</th>
<th>Role</th>
<th>Action</th>
</tr>";

while ($row) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='account_id[]' value=" . $row['account_id'] . " class='checkbox'></td>";
    echo " <td>" . $row['username'] . "</td>
    <td>" . $role = ($row['role'] == 1) ? 'Admin' : (($row['role'] == 2) ?
        'Worker' :  'Supplier') . "</td>";
    echo "<td id='action'> <button class='edit' data-accid=" . $row['account_id'] .
        " data-username=" . $row['username'] . " data-password=" . $row['password'] .
        " data-role=" .   $row['role'] . "><img src='images/edit.png'>Edit</button>
    </td>";
    echo "</tr>";
    $row = mysqli_fetch_array($result);
}
