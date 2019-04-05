<?php
require_once "functions.php";
session_start();

/*
source code of pdo.php:
<?php
 $dsn = "mysql:host=localhost;port=3306;dbname=misc";
 $username = "fred";
 $password = "zap";

 try{
 $pdo = new PDO($dsn,$username,$password);
}catch(Exception $e){
  echo("Internal error,please don't contact support");
  error_log("pde.php,SQL ERROR=".$e->getMessage());
}
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 ?>
*/

if(!isset($_GET['profile_id'])){
  return go_to_url('index.php', 'Missing profile_id', false);
}

require_once "pdo.php";
//$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :id');
$data = get_data($pdo, $_GET['profile_id']);
if($data === false){
  go_to_url('index.php', 'Could not load profile', false);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once "head.php" ?>
    <meta charset="utf-8">
    <title>Ilias Eloufir Profile View</title>
  </head>
  <body>
    <h1>Profile information</h1>
    <?php foreach ($data as $key => $value): ?>
      <?php if($key !== 'Position' && $key !== 'Education'): ?>
        <?php if($key == 'Headline' || $key == 'Summary'): ?>
    	    <p><?= $key ?>:<br> <?= htmlentities($value) ?></p>
        <?php else: ?>
    	    <p><?= $key ?>: <?= htmlentities($value) ?></p>
      	<?php endif; ?>
    	<?php endif; ?>
    <?php endforeach; ?>
  	<?php if(isset($data['Education'])): ?>
	    <p>Education</p>
	    <ul>
  		<?php foreach($data['Education'] as $pos_row): ?>
    		<li><?= htmlentities($pos_row['year']).': '.htmlentities($pos_row['name']) ?></li>
  		<?php endforeach; ?>
	    </ul>
  	<?php endif; ?>
  	<?php if(isset($data['Position'])): ?>
	    <p>Position</p>
	    <ul>
  		<?php foreach($data['Position'] as $pos_row): ?>
    		<li><?= htmlentities($pos_row['year']).': '.htmlentities($pos_row['description']) ?></li>
  		<?php endforeach; ?>
	    </ul>
  	<?php endif; ?>
    <p>
      <a href="index.php">Done</a>
    </p>
  </body>
</html>
