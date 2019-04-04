<?php
 $dsn = "mysql:host=localhost;port=3306;dbname=misc";
 $username = "ilel";
 $password = 'blacktail';

 try{
 $pdo = new PDO($dsn,$username,$password);
}catch(Exception $e){
  echo("Internal error,please don't contact support");
  error_log("pde.php,SQL ERROR=".$e->getMessage());
}
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 ?>
