<?php
if(!isset($_GET['profile_id'])){
    die("missing parameter");
}


require_once "pdo.php";
require_once "functions.php";

$data = get_data($pdo, $_GET['profile_id']);

header('Content-Type: application/json; charset=utf-8');
echo(json_encode($data, JSON_PRETTY_PRINT));
