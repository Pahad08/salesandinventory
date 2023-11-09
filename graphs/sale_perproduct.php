<?php
include 'openconn.php';

$sql = "SELECT products.name, products.price * sales.quantity as total
from sales
inner JOIN products on sales.product_id = products.product_id
where day(sales.sale_date) = day(CURRENT_DATE);";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div id="per-product">
    <div class="title">
        <h3>Sale per Product</h3>
    </div>
    <div id="product-sale"></div>
</div>

<script>
let currentmonth = new Date();
google.charts.load('current', {
    'packages': ['corechart']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    const data = google.visualization.arrayToDataTable([
        ['Sale per Product', 'Product'],
        <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "['" . $row['name'] . "', " . $row['total'] . "],";
            }
            ?>
    ]);

    const options = {
        title: 'Sale per Product This Day',
        chartArea: {
            width: '80%',
            height: '80%'
        }
    };

    const chart = new google.visualization.ColumnChart(document.getElementById('product-sale'));
    chart.draw(data, options);

}
</script>