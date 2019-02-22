<?php
require '../dbconnect.php';
echo "<h3>Users:</h3>";
$resUsers = $dbConnect->query("SELECT * FROM `users_tbl` order by `id` DESC");
$arUsers = $resUsers->fetchAll(PDO::FETCH_ASSOC);
foreach ($arUsers as $value) {
    echo implode(" | ", $value) . "<br>";
}
echo "<h3>Orders:</h3>";
$resOrders = $dbConnect->query("SELECT * FROM `orders_tbl` order by `id` DESC");
$arOrders = $resOrders->fetchAll(PDO::FETCH_ASSOC);
foreach ($arOrders as $value) {
    echo implode(" | ", $value) . "<br>";
}