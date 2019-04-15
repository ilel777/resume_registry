<?php 
require_once "pdo.php";
session_start();

$stmt = $pdo->query('SELECT first_name, last_name, profile_id, headline FROM profile');
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(isset($_SESSION['user_id'])){
	$data['loggedIn'] = true;
}
header('Content-Type: application/json; charset=utf-8');
echo(json_encode($data));
 ?>