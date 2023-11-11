<?php
include 'openconn.php';

$sql = "SELECT `description`, amount 
from expenses where MONTH(expense_date) = MONTH(CURRENT_TIME);";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div id="expense">
    <div class="title">
        <h3>Expenses This Month</h3>
    </div>
    <div id="expenses"></div>
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
                echo "['" . $row['description'] . "', " . $row['amount'] . "],";
            }
            ?>
        ]);

        const chart = new google.visualization.PieChart(document.getElementById('expenses'));
        chart.draw(data);

    }
</script>