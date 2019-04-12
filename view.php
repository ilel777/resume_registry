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
      <div class="container">
    <h1>Profile information</h1>
    <div id="table"></div>
    <p>
      <a href="index.php">Done</a>
    </p>
    <script type="text/x-handlebars-template" id="profile-table-template">
        <p>First Name: {{'First Name'}}</p>
        <p>Last Name: {{'Last Name'}}</p>
        <p>Email: {{Email}}</p>
        <p>Headline:<br> {{Headline}}</p>
        <p>Summary:<br> {{Summary}}</p>
        {{#if Education}}
        <p>Education:<br>
            <ul>
                {{#each Education}}
                <li>{{year}}:{{name}}</li>
                {{/each}}
            </ul>

        </p>
        {{/if}}
        {{#if Position}}
        <p>Postition:<br>
            <ul>
                {{#each Position}}
                <li>{{year}}:{{description}}</li>
                {{/each}}
            </ul>

        </p>
        {{/if}}
    </script>
    <script type="text/javascript">
    console.log('peace world!1');
     var raw_template = $('#profile-table-template').html();
     console.log(raw_template);
     var template = Handlebars.compile(raw_template);

    $.getJSON('profile.php?profile_id='+<?= $_GET['profile_id'] ?>, function(data){
         console.log(data);
         console.log(template);
         var rendered = template(data);
         console.log(rendered);
         console.log($('#table'));
         $('#table').html(rendered);
    })
    </script>
      </div>
  </body>
</html>
