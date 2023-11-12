<?php
include 'openconn.php';

$sql = "SELECT products.name, SUM(products.price * sales.quantity) as total
from sales
inner JOIN products on sales.product_id = products.product_id
where DAY(sales.sale_date) = DAY(CURRENT_DATE)
GROUP by sales.product_id";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div id="per-product">
    <div class="title">
        <h3>Sale per Product This Day</h3>
    </div>
    <div id="product-sale"></div>
</div>

<script>
google.charts.load('current', {
    'packages': ['corechart']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    const data = google.visualization.arrayToDataTable([
        ['Product Name', 'Total'],
        <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "['" . $row['name'] . "', " . $row['total'] . "], ";
            }
            ?>
    ]);

    const options = {
        animation: {
            duration: 1500,
            easing: 'out',
            startup: true
        }
    };

    const chart = new google.visualization.ColumnChart(document.getElementById('product-sale'));
    chart.draw(data, options);

}
</script>