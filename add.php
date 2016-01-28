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
	<li><a href='add.php' class="onpage">Add Photos</a></li>
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
	    <h1>Add a Photo</h1>
        
        <?php
             
            if(isset($_SESSION['user'])){   
            //the user did not fill out all the fields of the form
            if(isset($_POST['addsubmit']) && (empty($_POST['photocaption']) || empty($_POST['year'])|| empty($_POST['month']) || empty($_POST['day'])|| empty($_POST['albumtitle'])|| empty($_FILES['photo']) || $_FILES['photo']['size'] = 0)){
                print("<p class='error'>Please fill out all fields of the form!</p>");
            }
            
            //user submitted full form, input submission into database
            elseif(isset($_POST['addsubmit'])){
                
                $caption = strip_tags($_POST['photocaption']);
                $month = $_POST['month'];
                $day = $_POST['day'];
                $year = $_POST['year'];
                $album = $_POST['albumtitle'];
                
                //change to the required date format
                $date = "$month $day $year";
                $dateTaken = date('Y-m-d', strtotime($date));
                
                //insert new photo into the database
                $mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
                if($mysqli->errno){
                    print($mysqli->error);
                    exit();
                }
                else{
                    //insert photo into Photos database
                    $photoname = $_FILES['photo']['name'];
		    
                    //make sure the uniqueness of url(filename)
                    $query = "SELECT * FROM Photos WHERE Image_url = '".$photoname."'";
                    $match = $mysqli->query($query);
                    $match = $match->fetch_row();
                    if(!empty($match)){
                        print("<p class='error'>A photo with this file name already exists in our archives!
                              Please change the file name of your photo!</p>");
                    }
                    else{
                    
                    $query = "INSERT INTO Photos(caption, Image_url, Date_taken)
                    VALUES('".$caption."', '".$photoname."', '".$dateTaken."')";
                    $inserted = $mysqli->query($query);
                    
                    //insert photo into the ImgInAlbum table
                    $query = "SELECT pID FROM Photos WHERE Image_url LIKE '$photoname'";
                    $photoid = $mysqli->query($query);
                    $photoid = $photoid->fetch_row();
                    $photoid = $photoid[0];
                    
                    foreach($album as $item){
                        $query = "SELECT aID FROM Albums WHERE title LIKE '$item'";
                        $albumid = $mysqli->query($query);
                        $albumid = $albumid->fetch_row();
                        $albumid = $albumid[0];
                        
                        $query = "INSERT INTO ImgInAlbum(pID, aID) VALUES('".$photoid."', '".$albumid."')";
                        $idinserted = $mysqli->query($query);
                    }
                    
                    //file will be stored
                    $store  = "img/".basename($photoname);
                    
                    if($inserted && $idinserted && move_uploaded_file($_FILES['photo']['tmp_name'], $store)){
                        print("<p class='message'>Your photo has been successfully added!</p>");
                    }
                    
                    else{
                        print("<p class='error'>An error occurred when uploading your photo.
                              Please try again or choose a different photo!</p>");
                    }
                    $mysqli->close();
                }
                }
            }
            
            //supply the form
            include("forms/addphotoform.php");
            }
	    else{
                print("<p class='message'>You are not logged in! Please first log in <a href='login.php'>here</a>.</p>");
            }
           
        ?>
		
	    </div>
	</div>
    </body>
    </html>

