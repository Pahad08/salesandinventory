<?php
include 'openconn.php';

$sql = "SELECT sum(sales.quantity * products.price) as sale, sales.sale_date
from sales
inner JOIN products on sales.product_id = products.product_id 
where WEEK(sales.sale_date) = WEEK(CURRENT_DATE)
GROUP by DAY(sales.sale_date)
ORDER by DAY(sales.sale_date);";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
            while ($row = mysqli_fetch_array($result)) {
                echo "['" . $row['sale_date'] . "', " . $row['sale'] . "], ";
            }

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