<?php
try {
    $dbConnect = new PDO(
        sprintf("mysql:host=%s;dbname=%s;charset=%s", $settings["host"], $settings["name"], $settings["charset"]),
        $settings["user"], $settings["password"], $options);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die;
}
