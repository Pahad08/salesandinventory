<?php
session_start();
if (isset($_SESSION["worker"])) {
    echo "HEllo worker";
} else {
    header("location: login.php");
    exit();
}