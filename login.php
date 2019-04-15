<?php
session_start();
require_once "functions.php";

unset($_SESSION['name']);
unset($_SESSION['user_id']);
if(isset($_POST['cancel'])){
  return go_to_url('index.php');
}
$salt = 'XyZzy12*_';
if(isset($_POST['email']) && isset($_POST['pass'])){
  if(strlen($_POST['email'])>0 && strlen($_POST['pass'])>0){
    if(strpos($_POST['email'], '@') !== false){
      require_once "pdo.php";
      $check = hash('md5',$salt.$_POST['pass']);

      $stmt = $pdo->prepare('SELECT * FROM users WHERE password = :check AND email = :em');
      $stmt->execute(array(':check' => $check, ':em' => $_POST['email']));
      $row = false;
      if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        return go_to_url('index.php');
      }
      else{
        return go_to_url('login.php', 'Incorrect login info', false);
      }
    }
    else{
      return go_to_url('login.php', 'Invalid email address', false);
    }
  }
  else{
    return go_to_url('login.php', 'All fields are required', false);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once "boot_strap.php" ?>
    <meta charset="utf-8">
    <title>Ilias Eloufir's Login Page</title>
  </head>
  <body>
    <div class="container">
      <h1>Please Log In</h1>
      <?php flash_msg(); ?>
      <div class="row">
	<form method="post">
          <div class="form-group row">
            <label for="emailid" class="col-md-2 col-form-label">Email</label>
            <div class="col-md-10">
              <input class="form-control" id="emailid" name="email" type="text" placeholder="Email" />
            </div>
          </div>
          <div class="form-group row">
            <label for="passwordid" class="col-md-2 col-form-label">Password</label>
            <div class="col-md-10">
              <input class="form-control" id="passwordid" name="pass" type="password" placeholder="Email" />
            </div>
          </div>
	  <div class="form-group row">
	    <class class="col-md-1">
	      <button class="btn btn-primary" type="submit" onclick="return doValidate();">Log In</button>
	    </class>
	    <class class="col-md-11">
	      <button class="btn btn-primary" type="submit" name="cancel">Cancel</button>
	    </class>
	  </div>
	</form>
      </div>
    </div>

    <script type="text/javascript">
     function doValidate() {
       console.log('Validating...');
       try {
         addr = document.getElementById('emailid').value;
         pw = document.getElementById('passwordid').value;
         console.log("Validating addr="+addr+" pw="+pw);
         if (addr == null || addr == "" || pw == null || pw == "") {
           alert("Both fields must be filled out");
           return false;
         }
         if ( addr.indexOf('@') == -1 ) {
           alert("Invalid email address");
           return false;
         }
         return true;
       } catch(e) {
         return false;
       }
       return false;
     }
    </script>
  </body>
</html>
