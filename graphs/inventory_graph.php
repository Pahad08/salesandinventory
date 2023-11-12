<?php
include 'openconn.php';

$sql = "SELECT products.name, stocks.quantities 
from products
INNER JOIN stocks on products.product_id = stocks.product_id order by products.product_id;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div id="product">
    <div class="title">
        <h3>Inventory</h3>
    </div>
    <div id="products"></div>
</div>

<script>
google.charts.load('current', {
    'packages': ['corechart']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    const data = google.visualization.arrayToDataTable([
        ['Available Products', 'Stocks'],
        <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "['" . $row['name'] . "', " . $row['quantities'] . "],";
            }
            ?>
    ]);

    const chart = new google.visualization.PieChart(document.getElementById('products'));
    chart.draw(data);

}
</script>