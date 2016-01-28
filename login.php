<?php
session_start();
?>
    <!doctype html>
    <?php 
     require_once'config.php';
    ;?>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Shangyuan Niu's Photo Album</title>
        <link rel="stylesheet" type="text/css" href="css/css.css">
    </head>
    <body>
	<?php include 'header.php';?>
	<div class="contentbox">
	<div id="navbar">
    <ul>
	<li><a href='search.php'>All Photos/Search</a></li>
	<li><a href='albums.php'>All Albums</a></li>
	<li><a href='add.php'>Add Photos</a></li>
        <?php
        if(isset($_SESSION['user'])){
            print("<li><a href='logout.php'>Log Out</a></li>");
        }
	else{
	    print("<li><a href='login.php' class='onpage'>Log In</a></li>");
	}
        ?>
    </ul>
</div>
		
	<div class="content">
	    <?php
	
	//user is already logged in
	if(isset($_SESSION['user'])){
		$name = $_SESSION['name'];
		print("<p class='message'>You are currently logged in as $name.</p>");
	}
	
	//user is not logged in
	else{
	//form is submitted without filling out all the fields
	if(isset($_POST['submit']) && (empty($_POST['username']) || empty($_POST['password']))){
		print("<p class='error'>Please enter both valid a username and password before submitting!</p>");
		require("forms/loginform.php");
	}
	
	//all fields of the form are completed
	elseif(isset($_POST['username']) && isset($_POST['password'])){
		
		$password = strip_tags($_POST['password']);
		$username = strip_tags($_POST['username']);
		
		$mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
		if($mysqli->errno){
		    print($mysqli->error);
		    exit();
		}
		else{
			//check that username exists
			$query = "SELECT * FROM Users WHERE username LIKE '".$username."'";
			$user = $mysqli->query($query);
			$user = $user->fetch_assoc();
			
			//user does not exist, print error message.
			if(empty($user)){
			    print("<p class='error'>This username has not been registered.
				  Click <a href='signup.php'>here</a> to create a new account with this username!</p>");
			    require('forms/loginform.php');
			}
			
			//username exists, check that password matches
			else{
				
				//incorrect password
				if($user['password'] != hash('sha256', $password)){
				    print("<p class=\"error\">Incorrect password! Please try again!</p>");
				    require('forms/loginform.php');
					
				}
				
				//correct password, welcome user
				else{
				    $_SESSION['user'] = $username;
				    $_SESSION['name'] = $user['firstname'];
				    print("<p class='message'>Welcome, ".$_SESSION['name']."!</p>");
				}
			}
		$mysqli->close();
		}
	}
	//first time user visits site
	else{
		require('forms/loginform.php');
	}
	
	}
	?>
	</div>
    </body>
    </html>

