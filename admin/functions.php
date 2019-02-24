<?php
function insertUser(array $arrPost, PDO $dbConnect)
{
    $sqlInsertUser = "INSERT INTO `users_tbl` (`name`, `phone`, `email`) VALUES (?, ?, ?)";
    $insertUser = $dbConnect->prepare($sqlInsertUser);
    $insertUser->bindParam(1, $arrPost["name"]);
    $insertUser->bindParam(2, $arrPost["phone"]);
    $insertUser->bindParam(3, $arrPost["email"]);
    $insertUser->execute();
}

function getAddress($arrPost)
{
    return $arrPost["street"] . " / " . $arrPost["home"] . " / " . $arrPost["part"] . " / " . $arrPost["appt"] . " / " . $arrPost["floor"];
}

function insertOrder(array $arrPost, $userId, PDO $dbConnect)
{
    $callback = (isset($arrPost["callback"])) ? "N" : "Y";
    $sqlInsertOrder = "INSERT INTO `orders_tbl` (`id_user`, `address`, `comment`, `pay`, `callback`) VALUES (?, ?, ?, ?, ?)";
    $insertOrder = $dbConnect->prepare($sqlInsertOrder);
    $insertOrder->bindParam(1, $userId, PDO::PARAM_INT);
    $insertOrder->bindParam(2, getAddress($arrPost));
    $insertOrder->bindParam(3, $arrPost["comment"]);
    $insertOrder->bindParam(4, $arrPost["pay"]);
    $insertOrder->bindParam(5, $callback);
    $insertOrder->execute();
}

function putFileOrder($userId, PDO $dbConnect)
{
    $orderLastId = $dbConnect->query("SELECT `id` FROM orders_tbl WHERE `id_user` = '" . $userId. "' ORDER BY `id` DESC")->fetch(PDO::FETCH_ASSOC);
    $nameOrderFile = "orderid_" . $orderLastId["id"] . ".txt";
    $textOrder = "Ваш заказ будет доставлен по адресу: " . getAddress($_POST) . "\n";
    $textOrder .= "DarkBeefBurger за 500 рублей, 1 шт. \n";
    $countOrders = $dbConnect->query("SELECT COUNT(*) as count FROM `orders_tbl` WHERE `id_user` = '" . $userId . "'")->fetch(PDO::FETCH_ASSOC);
    $textOrder .= "Спасибо - это ваш " . $countOrders["count"] . " заказ";
    file_put_contents("./orders/" . $nameOrderFile, $textOrder);
}
