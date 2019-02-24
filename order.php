<?php
require "dbconnect.php";
require "./admin/functions.php";
try {
    $dbConnect->beginTransaction();
    $sqlUsers = "SELECT `id` FROM `users_tbl` WHERE `email` = '" . trim($_POST["email"]) . "'";
    $resUsers = $dbConnect->query($sqlUsers);
    if ($resUsers->rowCount() == 0) {
        insertUser($_POST, $dbConnect);
    }
    $user = $dbConnect->query($sqlUsers)->fetch(PDO::FETCH_ASSOC);
    insertOrder($_POST, $user["id"], $dbConnect);
    $dbConnect->commit();
} catch (Exception $e) {
    $dbConnect->rollback();
    echo "Error: " . $e->getMessage();
}
$orderLastId = $dbConnect->query("SELECT `id` FROM orders_tbl WHERE `id_user` = '" . $user["id"] . "' ORDER BY `id` DESC")->fetch(PDO::FETCH_ASSOC);
putFileOrder($orderLastId["id"], $user["id"], $dbConnect);
echo "<h1>Your order is submited! Please wait!</h1>";
