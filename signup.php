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
	   <h1>Sign up</h1>
        
        <p class='caption'>For both usernames and passwords, please only use capital letters, lower case letters and numbers</p>
        
        <?php
        if(isset($_SESSION['user'])){
            print("<p class='error'>You are currently logged in as ".$_SESSION['user'].". Sorry ".$_SESSION['name'].",
                  Click <a href='logout.php'>here</a> to log out before creating a new account!</p>");
        }
        
        //form is submitted but not completed
        elseif(isset($_POST['submit']) && (empty($_POST['firstname']) || empty($_POST['username']) || empty($_POST['password']))){
            print("<p class='error'>Please fill out all fields of the form before submitting!</p>");
            require('forms/signupform.php');
        }
        
        //form is submitted, all fields are completed
        elseif(isset($_POST['firstname']) && isset($_POST['username']) && isset($_POST['password'])){
            $firstname = strip_tags($_POST['firstname']);
            $username = strip_tags($_POST['username']);
            $password = strip_tags($_POST['password']);
            
            //check that username and password are only letters and numbers
            $regcheck = "/^[0-9a-zA-Z]+$/";
            $usercheck = preg_match($regcheck, $username);
            $pwcheck = preg_match($regcheck, $password);
            
            //username or password contains special characters or spaces
            if(!$usercheck || !$pwcheck){
                print("<p class='error'>Username and password must only contain letters and numbers.</p>");
                require('forms/signupform.php');
            }
            
            //username and password are correct
            else{
                $mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
		if($mysqli->errno){
		    print($mysqli->error);
		    exit();
		}
		else{
                    
                    //check that username does not already exist
                    $query="SELECT * FROM Users WHERE username LIKE '".$username."'";
                    $usermatch = $mysqli->query($query);
                    $usermatch = $usermatch->fetch_row();
                    if(!empty($usermatch)){
                        print("<p class='error'>This username already exists! Please select a different username.</p>");
                        require('forms/signupform.php');
                    }
                    
                    else{
                        $hashedpw = hash('sha256', $password);
                        $query = "INSERT INTO Users VALUES('".$username."', '".$hashedpw."', '".$firstname."')";
                        $newuser = $mysqli->query($query);
                        if($newuser){
                            print("<p class='message'>Account successfully created! Log in <a href='login.php'>here</a>
                                  </p>");
                            
                            require('forms/signupform.php');
                        }
                        else{
                            print("<p class='error'>An error occurred when creating your account. Please try again!</p>");
                            require('forms/signupform.php');
                        }
                    }
                    
                }
                $mysqli->close();
            }
        }
        else{
            require('forms/signupform.php');
        }
        ?>
        
	</div>
    </body>
    </html>

