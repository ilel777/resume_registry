<?php
//start session
session_start();
if(!(isset($_SESSION['name']) && isset($_SESSION['user_id']))){
  die('ACCESS DENIED');
  return;
}
require_once "functions.php";

//check if cancel was clicked
if(isset($_POST['cancel'])){
  return go_to_url('index.php');
}
//processing POST data
if(check_if_set()){
    //chech if the profile entries , the education entries and the position entries are not empty
    if(($msg=check_content()) !== true){
    	return go_to_url('add.php', $msg, false);
    }
    //preparing for data insertion
    $user_entry = array(':fn' => $_POST['first_name'],
                        ':ln' => $_POST['last_name'],
                        ':em' => $_POST['email'],
                        ':hl' => $_POST['headline'],
                        ':sm' => $_POST['summary']);

    require "pdo.php";
    $stmt = $pdo->prepare('INSERT INTO Profile(user_id, first_name, last_name, email, headline, summary) VALUES(:uid, :fn, :ln, :em, :hl, :sm)');
    $user_entry[':uid'] = $_SESSION['user_id'];
    if($stmt->execute($user_entry) === false){
      //if the insertion was not successful for wharever reason we display an error
    	return go_to_url('index.php', 'Error while adding new data', false);
    }

    $profile_id = $pdo->lastInsertId();
    //insert position entries
    if(insert_positions($pdo, $profile_id) !== true){
	return go_to_url('index.php', 'Error while adding new data', false);
    }

    //insert education entries
    if(insert_educations($pdo, $profile_id) !== true){
	return go_to_url('index.php', 'Error while adding new data', false);
    }

    return go_to_url('index.php', 'Profile added', true);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once "head.php" ?>
    <meta charset="utf-8">
    <title>Ilias Eloufir's Profile Add</title>
  </head>
  <body>
    <h1>Adding Profile for <?= htmlentities($_SESSION['name']) ?></h1>
    <?php flash_msg(); ?>
    <?php profile_form() ?>
	<script type="text/javascript">
    //Position's code
    var pos_counter = 0;
    var positions = [];
    for(var i=9; i>pos_counter; i--){
	positions.push(i);
    }
    const del_form = function(){
	positions.push($(this).parents('.position').attr('id').split('_')[1]);
        $(this).parents('.position').remove();
	return false;
    };
    const add_form = function(){
  		if(positions.length == 0){
  		    alert('max postion fields reached refresh the page to reset the counter');
  		    return false;
  		}
  		var current_pos = positions.pop();
  		$('#position_fields').append($(document.createElement('div')).attr({'id':'position_'+current_pos, 'class':'position'})
  		    .append($('<p>Year: </p>')
  			.append($('<br>'))
  			.append($(document.createElement('input')).attr({'type':'text','name':'year'+current_pos}))
  			.append($(document.createElement('input')).attr({'class':'del_form','type':'button','value':'-'}).click(del_form)))
  		    .append($(document.createElement('textarea')).attr({'name':'desc'+current_pos,'rows':'8','cols':'80'})));
  		return false;
    };

    $('#addPos').click(add_form);
    //Education's code
    var edu_counter = 0;
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
		    .attr({'class':'school_ui-autocomplete-input', 'size':'80', 'name':'edu_school'+current_edu, 'type':'text', 'autocomplete':'on'}).autocomplete({source:'school.php'}))));
	return false;
    }
    $('#addEdu').click(add_education);
    window.console && console.log($('.school_input'));
    $('.school_ui-autocomplete-input').autocomplete({source:'school.php'});
	</script>
  </body>
</html>
