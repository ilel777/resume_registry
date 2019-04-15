<?php
require_once "pdo.php";
require_once "functions.php";

session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once "head.php" ?>
    <meta charset="utf-8">
    <title>Ilias Eloufir's Resume Registry 02ddce85</title>
  </head>
  <body>
    <div class="container">
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
      <div id="profiles_table"></div>
      <?php if(isset($_SESSION['name'])): ?>
	<p>
          <a href="form.php">Add New Entry</a>
	</p>
      <?php endif; ?>
    </div>
  </body>
  <script type="text/x-handlebars-template" id="profiles-table-template">
    <div class="table-respensive">
      <table border="1" class="table table-striped">
	<thead clsss="thead-dark">
	  <tr>
	    <th>Name</th>
	    <th>Headline</th>
	    {{#if loggedIn }}<th>Action</th>{{/if}}
	  </tr>
        </thead>
	<tbody>
	  {{#each profiles}}
	  <tr>
	    <td>{{first_name}} {{last_name}}</td><td>{{headline}}</td>
	    {{#if ../loggedIn}}
	    <td><a href="form.php?profile_id={{profile_id}}">Edit</a> <a href="delete.php?profile_id={{profile_id}}">Delete</a></td>
	    {{/if}}
          </tr>
	  {{/each}}
        </tbody>
      </table>
    </div>
  </script>
  <script type="text/javascript">
   window.console && console.log('start script');
   var raw_template = $('#profiles-table-template').html();
   window.console && console.log(raw_template);
   var template = Handlebars.compile(raw_template);
   window.console && console.log(template);
   $.getJSON('profiles.php', function(data){
     window.console && console.log(data);
     var context = {};
     context.loggedIn = data['loggedIn'];
     delete data.loggedIn;
     context.profiles = data;
     window.console && console.log(context);
     var table = template(context);
     window.console && console.log(table);
     $('#profiles_table').html(table);
   })
  </script>
</html>
