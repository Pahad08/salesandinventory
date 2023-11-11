<?php
include 'openconn.php';

$sql1 = "SELECT sum(amount) as total_expense FROM `expenses` where MONTH(CURRENT_DATE);";
$stmt1 = mysqli_prepare($conn, $sql1);
mysqli_stmt_execute($stmt1);
$result1 = mysqli_stmt_get_result($stmt1);
$row1 = mysqli_fetch_array($result1);

$sql2 = "SELECT sum(sales.quantity * products.price) as sale, month(CURRENT_DATE)
from sales
inner JOIN products on sales.product_id = products.product_id 
where MONTH(sales.sale_date) = MONTH(CURRENT_DATE);";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$row2 = mysqli_fetch_array($result2);


if ($row1['total_expense'] - $row2['sale'] < 0) {
    $profit = abs($row2['sale'] - $row1['total_expense']);
} else {
    $profit = $row2['sale'] - $row1['total_expense'];
}

?>

<div id="profit">
    <div class="title">
        <h3>Profit</h3>
    </div>
    <div id="profits"></div>
</div>

<script>
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        const data = google.visualization.arrayToDataTable([
            ['Profit in this month', 'Profit'],
            <?php
            $month = date('F');
            echo "['" . "Profit In " . $month . "', " . $profit . "],";
            ?>
        ]);

        const chart = new google.visualization.BarChart(document.getElementById('profits'));
        chart.draw(data);

    }
</script>