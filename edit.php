<?php
require_once "pdo.php";

//chech if the profile entries and the position entries are not empty
if(($msg=check_content()) !== true){
    $url = 'edit.php?profile_id='.urlencode($_GET['profile_id']);
    return go_to_url($url, $msg, false);
}
$user_entry = array(':fn' => $_POST['first_name'],
		    ':ln' => $_POST['last_name'],
		    ':em' => $_POST['email'],
		    ':hl' => $_POST['headline'],
		    ':sm' => $_POST['summary'],
		    ':id' => $_POST['profile_id']);

$stmt = $pdo->prepare('UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em, headline = :hl, summary = :sm WHERE profile_id = :id');
if($stmt->execute($user_entry) === false){
//if update data failed return and display an error
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
