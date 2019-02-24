<?php
require 'dbconnect.php';
$email = trim($_POST["email"]);
try {
    $dbConnect->beginTransaction();
    $sqlUsers = "SELECT `id` FROM `users_tbl` WHERE `email` = '" . $email . "'";
    $resUsers = $dbConnect->query($sqlUsers);
    if ($resUsers->rowCount() == 0) {
        $userNew = "Y";
        $sqlInsertUser = "INSERT INTO `users_tbl` (`name`, `phone`, `email`) VALUES (?, ?, ?)";
        $insertUser = $dbConnect->prepare($sqlInsertUser);
        $insertUser->bindParam(1, $name);
        $insertUser->bindParam(2, $phone);
        $insertUser->bindParam(3, $email);
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $resUser = $insertUser->execute();
        $userNewId = $dbConnect->query($sqlUsers)->fetch(PDO::FETCH_ASSOC);
    } else {
        $userNew = "N";
        $user = $resUsers->fetch(PDO::FETCH_ASSOC);
    }
    $sqlInsertOrder = "INSERT INTO `orders_tbl` (`id_user`, `address`, `comment`, `pay`, `callback`) VALUES (?, ?, ?, ?, ?)";
    $insertOrder = $dbConnect->prepare($sqlInsertOrder);
    $insertOrder->bindParam(1, $userId, PDO::PARAM_INT);
    $insertOrder->bindParam(2, $address);
    $insertOrder->bindParam(3, $comment);
    $insertOrder->bindParam(4, $pay);
    $insertOrder->bindParam(5, $callback);
    $userId = ($userNew == "Y") ? $userNewId["id"] : $user["id"];
    $address = $_POST["street"] . " / " . $_POST["home"] . " / " . $_POST["part"] . " / " . $_POST["appt"] . " / " . $_POST["floor"];
    $comment = $_POST["comment"];
    $pay = $_POST["pay"];
    $callback = (isset($_POST["callback"])) ? "N" : "Y";
    $resOrder = $insertOrder->execute();
    $dbConnect->commit();
} catch (Exception $e) {
    $dbConnect->rollback();
    echo "Error: " . $e->getMessage();
}
$orderLastId = $dbConnect->query("SELECT `id` FROM orders_tbl WHERE `id_user` = '" . $userId . "' ORDER BY `id` DESC")->fetch(PDO::FETCH_ASSOC);
$nameOrderFile = "orderid_" . $orderLastId["id"] . ".txt";
$textOrder = "Ваш заказ будет доставлен по адресу: " . $address . "\n";
$textOrder .= "DarkBeefBurger за 500 рублей, 1 шт. \n";
$countOrders = $dbConnect->query("SELECT COUNT(*) as count FROM `orders_tbl` WHERE `id_user` = '" . $userId . "'")->fetch(PDO::FETCH_ASSOC);
$textOrder .= "Спасибо - это ваш " . $countOrders["count"] . " заказ";
file_put_contents("./orders/" . $nameOrderFile, $textOrder);
echo "<h1>Your order is submited! Please wait!</h1>";
