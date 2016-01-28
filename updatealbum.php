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
	<li><a href='albums.php' class="onpage">All Albums</a></li>
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
        if(isset($_SESSION['user'])){
            //the album to be edited
            if(isset($_REQUEST['album'])){
                $album = $_REQUEST['album'];
                $_SESSION['album'] = $album;
                $album = $_SESSION['album'];
            }
            else{
                $album = $_SESSION['album'];
            }
            
            $mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
            if($mysqli->errno){
                print($mysqli->error);
                exit();
            }
            
            else{
            //find the albumID
            $query = "SELECT aID FROM Albums WHERE title = '".$album."'";
            $aID = $mysqli->query($query);
            $aID = $aID->fetch_row();
            $aID = $aID[0];
            
            //the album has not yet been deleted
            if(!isset($_POST['delete'])){
                
                //the user clicked update
                if(isset($_POST['update'])){
                    //whether changes were successfully made
                    $updated = false;
                    
                    //the user inputed a new album name
                    if(!empty($_POST['albumtitle'])){
                        $newtitle = strip_tags($_POST['albumtitle']);
                        
                    //check that the album title doesn't already exist
                    $query = "SELECT * FROM Albums WHERE title = '".$newtitle."'";
                    $exists = $mysqli->query($query);
                    $exists = $exists->fetch_row();
                    
                    //album already exists
                    if(count($exists) != 0){
                        print("<p class='error'>An album named $newtitle already exists!
                              Please choose a different name.</p>");
                    }
                    
                    //album doesn't exist, update the title of the current album
                    else{
                        
                        $query = "UPDATE Albums SET title = '".$newtitle."' WHERE aID = '".$aID."'";
                        $titleupdate = $mysqli->query($query);
                        $updated = $titleupdate;
                        if(!$titleupdate){
                            print("<p class='error'>The album title could not be updated.</p>");
                        }
                        else{
                            //update the header of the page
                            $album = $newtitle;
                        }
                    }
                    }
                    
                   
                    if($updated){
                        $query = "UPDATE Albums SET date_modified = NOW() WHERE aID = '".$aID."'";
                        $mysqli->query($query);
                        print("<p class='message'>Your changes were successfully made!</p>");
                    }
                    
                }
                require("forms/updatealbumform.php");
            }
            
            //the user clicked delete
            else{
                $query = "DELETE FROM Albums WHERE aID = '".$aID."'";
                $adeleted = $mysqli->query($query);
                $query = "DELETE FROM ImgInAlbum WHERE aID = '".$aID."'";
                $cdeleted = $mysqli->query($query);
                if($cdeleted && $adeleted){
                    print("<p class='message'>This album has been successfully deleted. </p>");
                    print("<p>Click <a href='albums.php'>here</a> to go back to the albums page and add a new album!</p>");
                }
                else{
                    print("<p class='error'>A wild error appeared! Please try again!</p>");
                }
            }
            $mysqli->close();
            }
            
        }
        
         else{
            print("<p class='message'>You are not logged in! Please first log in <a href='login.php'>here</a>.</p>");
        }
        ?>
	</div>
    </body>
    </html>

