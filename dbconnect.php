<?php
try {
    $dbConnect = new PDO("mysql:host=localhost;dbname=project01_db", "root", "");
} catch (PDOException $e) {
    echo $e->getMessage();
    die;
}
