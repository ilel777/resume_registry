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
          <?php if($action_column && ($row['user_id'] == $_SESSION['user_id'])): ?>
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
	<p>
	Education: <input type="submit" id="addEdu" value="+">
	</p>
	<?php education_form(isset($data['Education'])? $data['Education']:false); ?>
      </p>
      Position: <input type="submit" id="addPos" value="+">
      <p>
	<?php position_form(isset($data['Position'])? $data['Position']:false); ?>

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
	if(isset($_POST['edu_year'.$i])){
	    error_log('$_POST[edu_year'.$i.']: '.$_POST['edu_year'.$i]);
	    if(strlen($_POST['edu_year'.$i]) == 0){
		return 'All fields are required';
	    }
	    if(!is_numeric($_POST['edu_year'.$i])){
		return 'Year must be a number';
	    }
	}
	if(isset($_POST['edu_school'.$i]) && strlen($_POST['edu_school'.$i]) == 0){
	    error_log('$_POST[edu_school'.$i.']: '.$_POST['edu_school'.$i]);
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
      <div id=<?= 'position_'.($pos_count+1) ?> class='position'>
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

<?php function education_form($data){
    $edu_cout = 0;?>
    <div id="edu_fields">
    <?php if($data !== false): ?>
    <?php foreach ($data as $row): ?>
      <div id=<?= 'edu_'.($edu_cout+1) ?> class='education'>
      <p>Year: 
	<input type="text" name=<?= 'edu_year'.($edu_cout)?> value=<?= htmlentities($row['year'])?>>
	<input class="del_edu" type="button" value="-">
      </p>
	<p>School: 
	<input class="school_ui-autocomplete-input" type="text" value=<?= htmlentities($row['name']) ?>  size="80" name=<?= 'edu_school'.($edu_cout) ?>  autocomplete="off">
	</p>
      </div>
      <?php $edu_cout += 1;?>
    <?php endforeach; ?>
  <?php endif; ?>
    </div>
<?php } ?>

<?php function get_data($pdo, $profile_id, $edit=false){
    $query_str = "SELECT first_name AS 'First Name',
    last_name AS 'Last Name',
    email AS Email,
    headline AS Headline,
    summary AS Summary
    FROM Profile WHERE profile_id = :pid";
    $params = array(':pid' => $profile_id);
    if($edit){
	$query_str.=" AND user_id = :uid";
	$params[':uid'] = $_SESSION['user_id'];
    }
  $stmt = $pdo->prepare($query_str);
  $stmt->execute($params);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row === false){
    return false;
  }
  //get position data
  $stmt = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :id ORDER BY rank");
  $stmt->execute(array(':id' => $profile_id));
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if($data !== false){
      $row['Position'] = $data;
  }
  //get education data
  $stmt = $pdo->prepare("SELECT * FROM Education JOIN Institution ON Education.institution_id = Institution.institution_id WHERE profile_id = :id ORDER BY rank");
  $stmt->execute(array(':id' => $profile_id));
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if($data !== false){
      $row['Education'] = $data;
  }
  return $row;
}

function insert_positions($pdo,$profile_id){
    $rank = 1;
    for($i = 1; $i<10; $i++){
	if(isset($_POST['year'.$i])){
	    $stmt = $pdo->prepare('INSERT INTO `Position`(`description`, `profile_id`, `rank`, `year`) VALUES(:desc, :pid, :rk, :yr)');
	    error_log('desc= '.$_POST['desc'.$i]);
	    error_log('year= '.$_POST['year'.$i]);
	    if($stmt->execute(array(':desc'=>$_POST['desc'.$i], ':pid'=> $profile_id, ':rk'=>$rank, 'yr'=>$_POST['year'.$i])) === false)   {
		return false;
	    }
	$rank++;
	}
    }
    return true;
}

function insert_educations($pdo,$profile_id){
    $rank = 1;
    for($i = 1; $i<10; $i++){
	if(isset($_POST['edu_school'.$i])){
	    $stmt = $pdo->prepare('SELECT * FROM Institution WHERE name = :ins_name');
	    if($stmt->execute(array(':ins_name' => $_POST['edu_school'.$i])) === false){
		return false;
	    }
	    //we can only have on row as a result or false since institution names are unique
	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    $ins_id = ($result === false) ? false : $result['institution_id'];

	    if(!$ins_id){
		$stmt = $pdo->prepare('INSERT INTO Institution(name) VALUES(:ins_name)');
		if($stmt->execute(array(':ins_name' => $_POST['edu_school'.$i])) ===false){
		    return false;
		}
		$ins_id = $pdo->lastInsertId();
	    }

	    $stmt = $pdo->prepare('INSERT INTO Education(profile_id, institution_id, rank, year) VALUES(:pid, :ins_id, :rk, :yr)');
	    if($stmt->execute(array(':ins_id' => $ins_id,
				    ':pid' => $profile_id,
				    ':rk' => $rank,
				    ':yr' => $_POST['edu_year'.$i])) ===false){
		return false;
	    }
	    $rank++;
	}
    }
    return true;
}
