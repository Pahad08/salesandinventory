<?php
include 'openconn.php';

$sql = "SELECT sum(sales.quantity * products.price) as sale, DAYNAME(CURRENT_DATE) as dayname
from sales
inner JOIN products on sales.product_id = products.product_id 
where WEEK(sales.sale_date) = WEEk(CURRENT_DATE);";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

?>

<div id="sales">
    <div class="title">
        <h3>Weekly Sales</h3>
    </div>
    <div id="sale"></div>
</div>

<script>
google.charts.load('current', {
    'packages': ['corechart']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    const data = google.visualization.arrayToDataTable([
        ['Sales', 'Weekly Sales'],
        <?php
            echo "['" . $row['dayname'] . "', " . $row['sale'] . "], ";
            ?>
    ]);

    const options = {
        title: 'Weekly Sales',
        animation: {
            duration: 1500,
            easing: 'out',
            startup: true
        }
    };
    const chart = new google.visualization.BarChart(document.getElementById('sale'));
    chart.draw(data, options);

}
</script>