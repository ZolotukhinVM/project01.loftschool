<?php
require "dbconnect.php";
require "./admin/functions.php";
try {
    $dbConnect->beginTransaction();
    if (getUserId($_POST["email"], $dbConnect) == 0) {
        insertUser($_POST, $dbConnect);
    }
    $userId = getUserId($_POST["email"], $dbConnect);
    insertOrder($_POST, $userId, $dbConnect);
    $dbConnect->commit();
} catch (Exception $e) {
    $dbConnect->rollback();
    echo "Error: " . $e->getMessage();
}
putFileOrder($userId, $dbConnect);
echo "<h1>Your order is submited! Please wait!</h1>";
