<?php
require 'dbconnect.php';
$email = trim($_POST["email"]);
$resUsers = $dbConnect->query("SELECT `id` FROM `users_tbl` WHERE `email` = '" . $email . "'");
if ($resUsers->rowCount() == 0) {
    $userNew = "Y";
    $dbConnect->query("INSERT INTO `users_tbl` (`name`, `phone`, `email`) VALUES ('" . $_POST["name"] . "', '" . $_POST["phone"] . "', '" . $_POST["email"] . "')");
} else {
    $userNew = "N";
    $user = $resUsers->fetch(PDO::FETCH_ASSOC);
}
$userId = ($userNew == "Y") ? $dbConnect->lastInsertId() : $user["id"];
$address = $_POST["street"] . " / " . $_POST["home"] . " / " . $_POST["part"] . " / " . $_POST["appt"] . " / " . $_POST["floor"];
$callback = (isset($_POST["callback"])) ? "N" : "Y";
$sqlOrders = "INSERT INTO `orders_tbl` (`id_user`, `address`, `comment`, `pay`, `callback`) VALUES (" . $userId . ", '" . $address . "' , '" . $_POST["comment"] . "', '" . $_POST["pay"] . "', '" . $callback . "')";
$dbConnect->query($sqlOrders);
//    if (!$res) {
//        print_r($dbConnect->errorInfo());
//    }
$nameOrderFile = $dbConnect->lastInsertId() . ".txt";
$textOrder = "Ваш заказ будет доставлен по адресу: " . $address . "\n";
$textOrder .= "DarkBeefBurger за 500 рублей, 1 шт. \n";
$textOrder .= "Спасибо - это ваш " . $dbConnect->query("SELECT `id` FROM `orders_tbl` WHERE `id_user` = '" . $userId . "'")->rowCount() . " заказ";
file_put_contents("./orders/" . $nameOrderFile, $textOrder);
echo "<h1>Your order is submited! Please wait!</h1>";
