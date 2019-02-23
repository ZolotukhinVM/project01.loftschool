<?php
require 'dbconnect.php';
$email = trim($_POST["email"]);
$resUsers = $dbConnect->query("SELECT `id` FROM `users_tbl` WHERE `email` = '" . $email . "'");
if ($resUsers->rowCount() == 0) {
    $userNew = "Y";
    $dbConnect->beginTransaction();
//    $insertUser = $dbConnect->query("INSERT INTO `users_tbl` (`name`, `phone`, `email`) VALUES ('" . $_POST["name"] . "', '" . $_POST["phone"] . "', '" . $_POST["email"] . "')");
    $sqlInsertUser = "INSERT INTO `users_tbl` (`name`, `phone`, `email`) VALUES (?, ?, ?)";
    $insertUser = $dbConnect->prepare($sqlInsertUser);
    $insertUser->bindParam(1, $name);
    $insertUser->bindParam(2, $phone);
    $insertUser->bindParam(3, $email);
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $resUser = $insertUser->execute();
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
//todo lastIsertId change for SELECT * FROM TABLE ORDER BY DESC
$userId = ($userNew == "Y") ? $dbConnect->lastInsertId() : $user["id"];
$address = $_POST["street"] . " / " . $_POST["home"] . " / " . $_POST["part"] . " / " . $_POST["appt"] . " / " . $_POST["floor"];
$comment = $_POST["comment"];
$pay = $_POST["pay"];
$callback = (isset($_POST["callback"])) ? "N" : "Y";
$resOrder = $insertOrder->execute();
//todo beginTransaction() & commit() + rollback whith try/catch
if ((isset($resUser) && $resUser) && $resOrder) {
    $dbConnect->commit();
} else {
    $dbConnect->rollBack();
}
//todo lastIsertId change for SELECT * FROM TABLE ORDER BY DESC
$nameOrderFile = $dbConnect->lastInsertId() . ".txt";
$textOrder = "Ваш заказ будет доставлен по адресу: " . $address . "\n";
$textOrder .= "DarkBeefBurger за 500 рублей, 1 шт. \n";
//todo SELECT COUNT()
$textOrder .= "Спасибо - это ваш " . $dbConnect->query("SELECT `id` FROM `orders_tbl` WHERE `id_user` = '" . $userId . "'")->rowCount() . " заказ";
file_put_contents("./orders/" . $nameOrderFile, $textOrder);
echo "<h1>Your order is submited! Please wait!</h1>";
