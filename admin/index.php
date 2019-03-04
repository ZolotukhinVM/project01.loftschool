<?php
require_once "../vendor/autoload.php";
require_once "../dbconnect.php";
require_once "functions.php";

$loader = new Twig\Loader\FilesystemLoader("templates");
$twig = new Twig\Environment($loader);
$template = $twig->loadTemplate("admin.html");
echo $template->render([
    "header1" => "Users",
    "users" => getUsers($dbConnect),
    "header2" => "Orders",
    "orders" => getOrders($dbConnect)
]);
