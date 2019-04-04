<?php
require_once "pdo.php";
require_once "functions.php";

/*
source code of functions.php:
<?php function flash_msg(){ ?>
  <?php if(isset($_SESSION['success'])): ?>
    <p style="color: green"><?= htmlentities($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php elseif(isset($_SESSION['failure'])): ?>
    <p style="color: red"><?= htmlentities($_SESSION['failure']) ?></p>
    <?php unset($_SESSION['failure']); ?>
  <?php endif; ?>
<?php } ?>

<?php function print_table($pdo,$action_column){
  $stmt = $pdo->query('SELECT * FROM Profile');
  $row = false;
  ?>
  <table border="1">
    <thead>
      <tr>
        <td>Name</td><td>Headline</td><?php if($action_column): ?><td>Action</td><?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
          <td><a href=<?= 'view.php?profile_id='.urlencode($row['profile_id']) ?>><?= htmlentities($row['first_name'].' '.$row['last_name']) ?></a></td>
          <td><?= htmlentities($row['headline']) ?></td>
          <?php if($action_column): ?>
            <td><a href=<?= 'edit.php?profile_id='.urlencode($row['profile_id']) ?>>Edit</a> <a href=<?= 'delete.php?profile_id='.urlencode($row['profile_id']) ?>>Delete</a></td>
          <?php endif; ?>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php } ?>

<?php
  function go_to_url($url, $msg = '', $success = true){
    error_log("edit.php ".$url." ".$msg." ".$success);
    if(strlen($msg) > 0){
      if($success){
        $_SESSION['success'] = $msg;
      }
      else{
        $_SESSION['failure'] = $msg;
      }
    }
    header("Location: ".$url);
    return;
  }
?>

<?php function profile_table($data = false,$profile_id){ ?>
    <form class="" method="post">
      <p>
        <label for="first_name">First Name: </label><input type="text" name="first_name" size="30" value=<?= $data ? htmlentities($data['First Name']) : '' ?>>
      </p>
      <p>
        <label for="last_name">Last Name: </label><input type="text" name="last_name" size="30" value=<?= $data ? htmlentities($data['Last Name']) : '' ?>>
      </p>
      <p>
        <label for="email">Email: </label><input type="text" name="email" size="60" value=<?= $data ? htmlentities($data['Email']) : '' ?>>
      </p>
      <p>
        <label for="headline">Headline: </label><input type="text" name="headline" size="80" value=<?= $data ? htmlentities($data['Headline']) : '' ?>>
      </p>
      <p>
        Summary:<br>
        <textarea name="summary" rows="8" cols="80"><?= $data ? htmlentities($data['Summary']) : '' ?></textarea>
      </p>
      Position: <input type="submit" id="addPos" value="+">
<?php position_form(isset($data['Position'])? $data['Position']:false); ?>
      <p>
        <?php if ($data !== false): ?>
          <input type="hidden" name="profile_id" value=<?= htmlentities($profile_id) ?>>
        <?php endif; ?>
        <input type="submit" value=<?= $data ? 'Save' : 'Add' ?>>
        <input type="submit" name="cancel" value="Cancel">
      </p>
<?php } ?>

<?php function check_if_set(){
    if(!(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))){
	return false;
    }
    else{
	return true;
    }
}?>

<?php function check_content(){
    if(!(strlen($_POST['first_name']) > 0 && strlen($_POST['last_name']) > 0 && strlen($_POST['email']) > 0 && strlen($_POST['headline']) > 0 && strlen($_POST['summary']) > 0)){
	return 'All fields are required';
    }
    for($i = 1; $i<10 ; $i++ ){
	if(isset($_POST['year'.$i])){
	    error_log('$_POST[year'.$i.']: '.$_POST['year'.$i]);
	    if(strlen($_POST['year'.$i]) == 0){
		return 'All fields are required';
	    }
	    if(!is_numeric($_POST['year'.$i])){
		return 'Year must be a number';
	    }
	}
	if(isset($_POST['desc'.$i]) && strlen($_POST['desc'.$i]) == 0){
	    error_log('$_POST[desc'.$i.']: '.$_POST['desc'.$i]);
	    return 'All fields are required';
	}
    }
    if(strpos($_POST['email'], '@') === false){
	return 'Email address must contain @';
    }
    return true;
}
?>
<?php function position_form($data){
  $pos_count = 0; ?>
  <div id="position_fields">
    <?php if($data !== false): ?>
    <?php foreach ($data as $row): ?>
      <div id=<?= 'position'.($pos_count+1) ?>>
        <p>
          Year: <input type="text" name=<?= 'year'.($pos_count+1)?> value=<?= htmlentities($row['year'])?>>
          <input type="button" value="-" class="del_form">
        </p>
        <textarea name=<?= 'desc'.($pos_count +1)?> rows="8" cols="80"><?= htmlentities($row['description'])?></textarea>
      </div>
      <?php $pos_count += 1;?>
    <?php endforeach; ?>
  <?php endif; ?>
  </div>
<?php } ?>

<?php function get_data($pdo, $profile_id){
  $stmt = $pdo->prepare("SELECT first_name AS 'First Name',
    last_name AS 'Last Name',
    email AS Email,
    headline AS Headline,
    summary AS Summary
    FROM Profile WHERE profile_id = :id");
  $stmt->execute(array(':id' => $profile_id));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row === false){
    return false;
  }
  $stmt = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :id");
  $stmt->execute(array(':id' => $profile_id));
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if($data !== false){
      $row['Position'] = $data;
  }
  return $row;
}
*/

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
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once "boot_strap.php" ?>
    <meta charset="utf-8">
    <title>Ilias Eloufir's Resume Registry f08c6451</title>
  </head>
  <body>
    <h1>Ilias Eloufir's Resume Registry</h1>
    <?php flash_msg(); ?>
    <?php if(!isset($_SESSION['name']) || !isset($_SESSION['user_id'])): ?>
      <p>
        <a href="login.php">Please log in</a>
      </p>
    <?php else: ?>
      <p>
        <a href="logout.php">Logout</a>
      </p>
    <?php endif; ?>
    <?php print_table($pdo, isset($_SESSION['name']) && isset($_SESSION['user_id'])); ?>
    <?php if(isset($_SESSION['name'])): ?>
      <p>
        <a href="add.php">Add New Entry</a>
      </p>
    <?php endif; ?>
  </body>
</html>
