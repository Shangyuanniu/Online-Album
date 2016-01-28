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
	    print("<li><a href='login.php'>Log In</a></li>");
	}
        ?>
    </ul>
</div>
		
	<div class="content">
	    <?php
        //log out the user
        if(isset($_SESSION['user'])){
            $name = $_SESSION['name'];
            unset($_SESSION['user']);
            unset($_SESSION['name']);
            unset($_SESSION['image']);
            unset($_SESSION['album']);
            session_destroy();
            
            print("<p class='message'>You have been successfully logged out, $name!</p>");
        }
        
        //no user logged in
        else{
            print("<p class='error'>Please log in <a href='login.php'>here</a> before you log out.</p>");
        }
        
        ?>
	</div>
    </body>
    </html>

