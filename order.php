<?php
require "dbconnect.php";
require "./admin/functions.php";
try {
    $dbConnect->beginTransaction();
    $sqlUser = "SELECT `id` FROM `users_tbl` WHERE `email` = '" . trim($_POST["email"]) . "'";
    $resUser = $dbConnect->query($sqlUser);
    if ($resUser->rowCount() == 0) {
        insertUser($_POST, $dbConnect);
    }
    $user = $dbConnect->query($sqlUser)->fetch(PDO::FETCH_ASSOC);
    insertOrder($_POST, $user["id"], $dbConnect);
    $dbConnect->commit();
} catch (Exception $e) {
    $dbConnect->rollback();
    echo "Error: " . $e->getMessage();
}
putFileOrder($user["id"], $dbConnect);
echo "<h1>Your order is submited! Please wait!</h1>";
