<?php
require "./vendor/autoload.php";
require "./settings.php";
require "./dbconnect.php";
require "./admin/functions.php";
try {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new Exception("Email is not valid!");
    }
    $dbConnect->beginTransaction();
    if (getUserId($email, $dbConnect) == 0) {
        insertUser($_POST, $dbConnect);
    }
    $userId = getUserId($email, $dbConnect);
    insertOrder($_POST, $userId, $dbConnect);
    $dbConnect->commit();
} catch (PDOException $e) {
    $dbConnect->rollback();
    echo "Error: " . $e->getMessage();
    die;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    die;
}
putFileOrder($userId, getTextOrder($userId, $dbConnect), $dbConnect);
//sendMail($email, "test");

echo "<h1>Your order is submited! Please wait!</h1>";
