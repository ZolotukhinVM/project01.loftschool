<?php
try {
    $dbConnect = new PDO("mysql:host=localhost;dbname=project01_db", "root", "");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die;
}
