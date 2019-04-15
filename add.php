<?php
require_once "functions.php";
//chech if the profile entries , the education entries and the position entries are not empty
if(($msg=check_content()) !== true){
    return go_to_url('form.php', $msg, false);
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
