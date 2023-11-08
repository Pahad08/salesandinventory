<?php
include 'openconn.php';

$sql = "SELECT sum(sales.quantity * products.price) as sale, sales.sale_date
from sales
inner JOIN products on sales.product_id = products.product_id 
where DAY(sales.sale_date) = DAY(CURRENT_DATE);";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div id="sales">
    <div class="title">
        <h3>Sales</h3>
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
        ['Sales', 'Total Sales'],
        <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "['" . $row['sale_date'] . "', " . $row['sale'] . "],";
            }
            ?>
    ]);

    const options = {
        title: 'Total Sales Today',
    };

    var size = {
        chartArea: {
            width: '80%',
            height: '80%'
        },
    };

    const chart = new google.visualization.BarChart(document.getElementById('sale'));
    chart.draw(data, options);

    function drawChart() {
        chart.draw(data, options);
    }

    window.addEventListener('resize', drawChart);

    chart.draw(data, options);

}
</script>