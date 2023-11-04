<?php

include 'openconn.php';
if (isset($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
} else {
    $page_number = 1;
}

$number_per_page = 5;
$offset = ($page_number - 1) * $number_per_page;
$nextpage = $page_number + 1;
$previouspage = $page_number - 1;

$sql = "SELECT * from products LIMIT $number_per_page OFFSET $offset;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$sql2 = "SELECT count(product_id) as total from products;";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$Row = mysqli_fetch_array($result2);
mysqli_close($conn);

$total_records = $Row['total'];
$total_pages = ceil($total_records / $number_per_page);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
        <title>Products</title>
    </head>

    <body>


        <div class="body">

            <div class="body-content">

                <div class="search">
                    <input type="text" id="search" placeholder="Search" onkeydown="showHint(this.value)">
                </div>

                <table id="table">
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Kilogram</th>
                        <th>Price</th>
                        <th>Edit</th>
                    </tr>
                    <form action="delete/deleteproduct.php" id="deleteproduct" method="post">
                        <?php while ($row) { ?>
                        <tr>
                            <td><input type="checkbox" name="product_id[]" value="<?php echo $row['product_id']; ?>"
                                    class="checkbox"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['kilogram']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td id="action"> <button class="edit" data-productid="<?php echo $row['product_id']; ?>"
                                    data-name="<?php echo $row['name']; ?>"
                                    data-kilogram="<?php echo $row['kilogram']; ?>"
                                    data-price="<?php echo $row['price']; ?>"><img src="images/edit.png"
                                        alt="">Edit</button>
                            </td>

                            <?php $row = mysqli_fetch_array($result);
                    } ?>
                        </tr>
                    </form>

                </table>

                <div class="page">

                    <p><?php echo "Page " . "<b>$page_number </b>" . " of " . "<b>$total_pages</b>" ?></p>

                    <ul class="page-list">
                        <li><a <?php if ($page_number != 1) {
                                echo "href=products.php?page_number=" . $previouspage;
                            } ?>>&laquo;</a></li>

                        <?php for ($i = 0; $i < $total_pages; $i++) { ?>
                        <li><a href="<?php echo "products.php?page_number=" . $i + 1; ?>"><?php echo $i + 1; ?></a>
                        </li>
                        <?php } ?>


                        <li><a <?php if ($page_number != $total_pages && $total_pages != 0) {
                                echo "href=products.php?page_number=" . $nextpage;
                            } ?>>&raquo;</a></li>
                    </ul>

                </div>

            </div>
        </div>


        </div>
    </body>
    <script>
    function showHint(str) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementById("table").innerHTML =
                this.responseText;
        }
        xhttp.open("GET", "ajax_search/productlist.php?name=" + str);
        xhttp.send();
    }
    </script>

</html>