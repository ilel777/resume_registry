<?php session_start();
if(!(isset($_SESSION['name']) && isset($_SESSION['user_id']))){
  die('ACCESS DENIED');
  return;
}
require_once "functions.php";

//checking the GET data
if(!isset($_GET['profile_id'])){
  return go_to_url('index.php', 'Missing profile_id', false);
}
//if cancel is set we redirect to index and skip the rest of code
if(isset($_POST['cancel'])){
  return go_to_url('index.php');
}

require_once "pdo.php";

if(isset($_POST['profile_id'])){
  $stmt = $pdo->prepare('DELETE FROM Profile WHERE profile_id = :id');
  if($stmt->execute(array(':id' => $_POST['profile_id'])) === false){
    return go_to_url('index.php', 'Could not load profile', false);
  }
  else{
    return go_to_url('index.php', 'Profile deleted', true);
  }
}

$read_stmt = $pdo->prepare('SELECT first_name as "First Name", last_name as "Last Name" FROM Profile WHERE profile_id = :id');
$read_stmt->execute(array(':id' => $_GET['profile_id']));
$data = $read_stmt->fetch(PDO::FETCH_ASSOC);
if($data === false){
  return go_to_url('index.php', 'Could not load profile', false);
}

?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once "boot_strap.php" ?>
    <meta charset="utf-8">
    <title>Ilias Eloufir' Profile Delete</title>
  </head>
  <body>
    <h1>Deleteing Profile</h1>
    <form method="post">
      <?php foreach ($data as $key => $value): ?>
        <p><?= $key ?>: <?= htmlentities($value) ?></p>
      <?php endforeach; ?>
      <input type="hidden" name="profile_id" value=<?= htmlentities($_GET['profile_id']) ?>>
      <input type="submit" value="Delete">
      <input type="submit" name="cancel" value="Cancel">
    </form>
  </body>
</html>
