<?php
//start session
session_start();
if(!(isset($_SESSION['name']) && isset($_SESSION['user_id']))){
  die('ACCESS DENIED');
  return;
}
require_once "functions.php";
require_once "pdo.php";

//checking the GET data
if(isset($_GET['profile_id'])){
  //return go_to_url('index.php', 'Missing profile_id', false);
  //getting profile data from the database
  $data = get_data($pdo, $_GET['profile_id'],true);
  if($data === false){
    return go_to_url('index.php', 'Could not load profile', false);
  }
}
//if cancel is set redirect to index and skip the rest of code
if(isset($_POST['cancel'])){
  return go_to_url('index.php');
}


//processing POST data
if(check_if_set()){
  if(isset($_POST['profile_id'])){
    return require_once "edit.php";
  }
  else{
    return include_once "add.php";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once "head.php" ?>
    <meta charset="utf-8">
    <title>Ilias Eloufir's Profile Edit</title>
  </head>
  <body>
<div class="container">
  <h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?></h1>
    <?php flash_msg(); ?>
  <div id="form"></div>
</div>
<?php require_once "form_renderer.php"; ?>
    </body>
</html>
