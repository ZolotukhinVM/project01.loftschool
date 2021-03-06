<?php
function getUserId(string $mail, PDO $dbConnect): int
{
    $sqlUser = "SELECT `id` FROM `users_tbl` WHERE `email` = '$mail'";
    $resUser = $dbConnect->query($sqlUser);
    $rowCount = $resUser->rowCount();
    $arUser = $resUser->fetch(PDO::FETCH_ASSOC);
    return ($rowCount == 0) ? $rowCount : $arUser ["id"];
}

function insertUser(array $arrPost, PDO $dbConnect)
{
    $sqlInsertUser = "INSERT INTO `users_tbl` (`name`, `phone`, `email`) VALUES (:name, :phone, :email)";
    $insertUser = $dbConnect->prepare($sqlInsertUser);
    $insertUser->execute(['name' => $arrPost['name'], 'phone' => $arrPost['phone'], 'email' => $arrPost['email']]);
}

function getAddress(array $arrPost): string
{
    $arrAddress = [
        $arrPost["street"],
        $arrPost["home"],
        $arrPost["part"],
        $arrPost["appt"],
        $arrPost["floor"]
    ];
    return implode(" / ", $arrAddress);
}

function insertOrder(array $arrPost, int $userId, PDO $dbConnect)
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

function getCountOrders ($userId, PDO $dbConnect) {
    return $dbConnect->query("SELECT COUNT(*) FROM `orders_tbl` WHERE `id_user` = $userId")->fetchColumn();
}

function getTextOrder($userId, $dbConnect): string
{
    $textOrder = "Ваш заказ будет доставлен по адресу: " . getAddress($_POST) . "\n";
    $textOrder .= "DarkBeefBurger за 500 рублей, 1 шт. \n";
    $textOrder .= "Спасибо - это ваш " . getCountOrders($userId, $dbConnect) . " заказ";
    return $textOrder;
}

function putFileOrder(int $userId, string $textOrder, PDO $dbConnect)
{
    $orderLastId = $dbConnect->query("SELECT `id` FROM orders_tbl WHERE `id_user` = '" . $userId . "' ORDER BY `id` DESC")->fetch(PDO::FETCH_ASSOC);
    $nameOrderFile = "orderid_" . $orderLastId["id"] . ".txt";
    file_put_contents("./orders/" . $nameOrderFile, $textOrder);
}

function sendMail(string $email, string $textOrder)
{
    $transport = (new Swift_SmtpTransport('smtp.yandex.ru', 465, 'ssl'))
        ->setUsername($mailconfig["login"])
        ->setPassword($mailconfig["password"]);
    $mailer = new Swift_Mailer($transport);
    $message = (new Swift_Message('New order'))
        ->setFrom(['ZolotukhinVM@ya.ru' => 'ZolotukhinVM'])
        ->setTo([$email])
        ->setBody($textOrder);
    $mailer->send($message);
}

function getUsers(PDO $dbConnect): array
{
    return $dbConnect->query("SELECT * FROM `users_tbl` order by `id` DESC")->fetchAll();
}

function getOrders(PDO $dbConnect): array
{
    return $dbConnect->query("SELECT * FROM `orders_tbl` order by `id` DESC")->fetchAll();
}
