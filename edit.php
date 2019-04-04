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
//if cancel is set redirect to index and skip the rest of code
if(isset($_POST['cancel'])){
  return go_to_url('index.php');
}

require_once "pdo.php";

$url = 'edit.php?profile_id='.urldecode($_GET['profile_id']);
//processing POST data
if(check_if_set() && isset($_POST['profile_id'])){
    //chech if the profile entries and the position entries are not empty
    if(($msg=check_content()) !== true){
    	return go_to_url('add.php', $msg, false);
    }
  $user_entry = array(':fn' => $_POST['first_name'],
                      ':ln' => $_POST['last_name'],
                      ':em' => $_POST['email'],
                      ':hl' => $_POST['headline'],
                      ':sm' => $_POST['summary'],
                      ':id' => $_POST['profile_id']);

  $stmt = $pdo->prepare('UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em, headline = :hl, summary = :sm WHERE profile_id = :id');
  if($stmt->execute($user_entry) === false){
    return go_to_url('index.php', 'Error while updating data', false);
  }

  //delete the postion rows related to the profile_id
  $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id = :pid');
  $stmt->execute(array(':pid' => $_POST['profile_id']));
  //delete the education rows related to the profile_id
  $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id = :pid');
  $stmt->execute(array(':pid' => $_POST['profile_id']));

    //insert updated position entries
    if(insert_positions($pdo, $_POST['profile_id']) !== true){
	return go_to_url('index.php', 'Error while adding new data', false);
    }

    //insert updater education entries
    if(insert_educations($pdo, $_POST['profile_id']) !== true){
	return go_to_url('index.php', 'Error while adding new data', false);
    }
  return go_to_url('index.php', 'Profile updated', true);
}

//getting profile data from the database
$data = get_data($pdo, $_GET['profile_id'],true);
if($data === false){
  return go_to_url('index.php', 'Could not load profile', false);
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
    <h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?></h1>
    <?php flash_msg(); ?>
    <?php profile_table($data, $_GET['profile_id']); ?>
  </body>
  <script type="text/javascript">
    //Position's code
    var pos_counter = Number(<?= isset($data['Position']) ? count($data['Position']) : 0 ?>);
    var positions = [];
    for(var i=9; i>pos_counter; i--){
	positions.push(i);
    }
    const del_form = function(){
	window.console && console.log(positions);
	positions.push($(this).parents('.position').attr('id').split('_')[1]);
        $(this).parents('.position').remove();
	return false;
    };
    const add_form = function(){
		window.console && console.log(positions);
  		if(positions.length == 0){
  		    alert('max postion fields reached');
  		    return false;
  		}
  		var current_pos = positions.pop();
  		$('#position_fields').append($(document.createElement('div')).attr({'id':'position_'+current_pos,'class':'position'})
  		    .append($('<p>Year: </p>')
  			.append($('<br>'))
  			.append($(document.createElement('input')).attr({'type':'text','name':'year'+current_pos}))
  			.append($(document.createElement('input')).attr({'class':'position','type':'button','value':'-'}).click(del_form)))
  		    .append($(document.createElement('textarea')).attr({'name':'desc'+current_pos,'rows':'8','cols':'80'})));
  		return false;
    };

    $('.del_form').click(del_form);
    $('#addPos').click(add_form);
    //Education's code
    var edu_counter = Number(<?= isset($data['Education']) ? count($data['Education']) : 0 ?>);
    var educations = [];
    for(var i=9; i>edu_counter; i--){
	educations.push(i);
    }
    const del_education = function(){
	window.console && console.log($(this).parents('.education'));
	educations.push(($(this)).parents('.education').attr('id').split('_')[1]);
	$(this).parents('.education').remove();
	return false;
    }
    const add_education = function(){
	if(educations.length == 0){
	    alert('max education fields reached');
	    return false;
	}
	var current_edu = educations.pop();
	$('#edu_fields').append($('<div id=\"education_'+current_edu+'\" class=education></div>')
	    .append($('<p>Year: </p>')
		.append($(document.createElement('input')).attr({'name':'edu_year'+current_edu, 'type':'text'}))
		.append($(document.createElement('input')).attr({'value':'-', 'type':'button'}).click(del_education)))
	    .append($('<p>School: </p>')
		.append($(document.createElement('input'))
		    .attr({'class':'school_ui-autocomplete-input', 'size':'80', 'name':'edu_school'+current_edu, 'type':'text', 'autocomplete':'off'})
		    .autocomplete({source:'school.php'}))));
	return false;
    }
    $('.school_ui-autocomplete-input').autocomplete({source:['hello','bye','none']});
    window.console && console.log($('.school_ui-autocomplete-input'));
    $('#addEdu').click(add_education);
    $('.del_edu').click(del_education);
  </script>
</html>
