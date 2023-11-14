<?php

include '../openconn.php';

if (!empty($_GET['name'])) {
    $name = "%" . $_GET['name'] . "%";
    $sql = "SELECT workers.*, accounts.username
    FROM workers
    LEFT join accounts on workers.account_id = accounts.account_id
    where workers.f_name LIKE ? or workers.l_name LIKE ?
    LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $name, $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
} else {
    $sql = "SELECT workers.*, accounts.username
    FROM workers
    LEFT join accounts on workers.account_id = accounts.account_id
    LIMIT 5 OFFSET 0;";
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
<th>Account</th>
<th>Edit</th>
</tr>";

while ($row) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='worker_id[]' value=" . $row['worker_id'] . " class='checkbox'></td>";
    echo " <td>" . $row['f_name'] . " " . $row['l_name'] . "</td>
    <td>" .  $row['contact_number'] . "</td>
    <td>" . $row['username'] . "</td>";
    echo "<td id='action'> <button class='edit' data-workerid=" . $row['worker_id'] .
        " data-fname=" . $row['f_name'] . " data-lname=" . $row['l_name'] .
        " data-number= " . $row['contact_number'] .
        ' data-accid="' . $row['account_id'] . '"  data-username=' . $row['username'] .  "><img src='images/edit.png'>Edit</button>
</td>";
    echo "</tr>";
    $row = mysqli_fetch_array($result);
}
