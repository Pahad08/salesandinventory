<?php
session_start();
include '../openconn.php';

if (!empty($_GET['name'])) {
    $name = "%" . $_GET['name'] . "%";
    $sql = "SELECT `transaction`.*, products.name, suppliers.f_name, suppliers.l_name
    FROM `transaction`
    LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
    LEFT JOIN products on `transaction`.`product_id` = products.product_id
    where suppliers.f_name LIKE ? or suppliers.l_name LIKE ? or products.name LIKE ?
    LIMIT 5;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $name, $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    $stmt_sched = mysqli_prepare($conn, "SELECT `transaction`.*, products.name
    FROM `transaction`
    LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
    LEFT JOIN products on `transaction`.`product_id` = products.product_id
    where suppliers.account_id = ? and products.name LIKE ?
    LIMIT 5;");
    mysqli_stmt_bind_param($stmt_sched, "is", $_SESSION["supplier"], $name);
    mysqli_stmt_execute($stmt_sched);
    $result_sched = mysqli_stmt_get_result($stmt_sched);
    $row_sched = mysqli_fetch_array($result_sched);
} else {
    $sql = "SELECT `transaction`.*, products.name, suppliers.f_name, suppliers.l_name
    FROM `transaction`
    LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
    LEFT JOIN products on `transaction`.`product_id` = products.product_id
    LIMIT 5 OFFSET 0;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    $stmt_sched = mysqli_prepare($conn, "SELECT `transaction`.*, products.name
    FROM `transaction`
    LEFT JOIN suppliers on `transaction`.supplier_id = suppliers.supplier_id
    LEFT JOIN products on `transaction`.`product_id` = products.product_id
    where suppliers.account_id = ?
    LIMIT 5 OFFSET 0;");
    mysqli_stmt_bind_param($stmt_sched, "i", $_SESSION["supplier"]);
    mysqli_stmt_execute($stmt_sched);
    $result_sched = mysqli_stmt_get_result($stmt_sched);
    $row_sched = mysqli_fetch_array($result_sched);
}

mysqli_close($conn);

if (isset($_SESSION["admin"]) && isset($_SESSION["admin_username"])) {

    echo "<th></th>
    <th>Supplier Name</th>
    <th>Transaction Date</th>
    <th>Delivery Schedule</th>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Action</th>";

    while ($row) {
        echo "<tr>";
        echo "<td><input type='checkbox' name='transaction_id[]' value=" . $row['transaction_id'] . " class='checkbox'></td>";
        echo " <td>" . $row['f_name'] . " " . $row['l_name'] . "</td>";

        echo "<td>" .  $row['transaction_date'] . "</td>
    <td>" . $row['delivery_schedule'] . "</td>
    <td>" . $row['name'] . "</td>
    <td>" . $row['quantity'] . "</td>";
        echo "<td id='sched-btns'> <button class='edit'";
        echo "  data-transactionid=" . $row['transaction_id'];
        echo "  data-lname=" . $row['l_name'];
        echo "  data-fname=" . $row['f_name'];
        echo "  data-supplierid=" . $row['supplier_id'];
        echo "  data-quantity=" . $row['quantity'];
        echo "  data-prodid=" . $row['product_id'];
        echo "  data-prodname=" . $row['name'];
        echo "  data-transaction=" . $row['transaction_date'];
        echo "  data-delivery=" . $row['delivery_schedule'] . ">";
        echo "    <img src='images/edit.png'>Edit</button>";
        echo "<button id='receive' class='receive'";
        echo "  data-id=" . $row['transaction_id'] . ">";
        echo "<img src='images/received.png'>Receive</button>";
        echo "</tr>";
        $row = mysqli_fetch_array($result);
    }
} else {
    echo "<th>Transaction Date</th>
    <th>Delivery Schedule</th>
    <th>Product Name</th>
    <th>Quantity</th>";
    while ($row_sched) {
        echo "<tr>";
        echo "<td>" .  $row_sched['transaction_date'] . "</td>
             <td>" . $row_sched['delivery_schedule'] . "</td>
             <td>" . $row_sched['name'] . "</td>
              <td>" . $row_sched['quantity'] . "</td>";
        echo "</tr>";
        $row_sched = mysqli_fetch_array($result_sched);
    }
}