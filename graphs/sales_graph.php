<?php
include 'openconn.php';

function ArrayAdd($index, $row, $weeks, $weekly_sales)
{
    $sub_array = array();
    array_push($sub_array, $weeks[$index]);
    array_push($sub_array, $row);
    array_push($weekly_sales, $sub_array);
    return $weekly_sales;
}

$sql = "SELECT sum(sales.quantity * products.price) as sale
from sales
inner JOIN products on sales.product_id = products.product_id 
where WEEK(sales.sale_date) = WEEk(CURRENT_DATE) or WEEK(sales.sale_date) = WEEK(CURRENT_DATE)-1
group by WEEK(sales.sale_date);";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_rows = mysqli_num_rows($result);
$row = mysqli_fetch_array($result);

$weekly_sales = array();
$weeks = array("Last Week", "This Week");
$index = ($total_rows == 1) ? 1 : 0;

if ($total_rows == 1) {
    $weekly_sales =  ArrayAdd($index, $row['sale'], $weeks, $weekly_sales);
} else {
    while ($row) {
        $weekly_sales = ArrayAdd($index, $row['sale'], $weeks, $weekly_sales);
        $index++;
        $row = mysqli_fetch_array($result);
    }
}

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
            for ($i = 0; $i < count($weekly_sales); $i++) {
                for ($j = 0; $j < 1; $j++) {
                    echo "['" . $weekly_sales[$i][$j] . "', " . $weekly_sales[$i][$j + 1] . "], ";
                }
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