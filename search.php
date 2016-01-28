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
	<li><a href='search.php' class="onpage">All Photos/Search</a></li>
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
	    <h1>View All Photos</h1>
        
        <?php
        if(!isset($_SESSION['user'])){
        print("<p class='message'>To add your own photos, log in <a href='login.php'>here</a>!</p>");
        }
        $mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
        if($mysqli->errno){
            print($mysqli->error);
            exit();
        }
        
        else{
            
        print("<p>Search photos</p>");
        require('forms/searchform.php');
        
        //form submitted without entering all fields
        if(isset($_POST['search']) && (empty($_POST['captiontext']) || !isset($_POST['caption']))){
            print("<p class='error'>Please complete both fields of the form before submitting!</p>");
        }
        
        //form submitted with all fields
        elseif(!empty($_POST['captiontext']) && isset($_POST['caption'])){
            $captiontext = strip_tags($_POST['captiontext']);
            $caption = $_POST['caption'];

            //radio button "contains" is selected
            if($caption == "contains"){
                $query = "SELECT image_url FROM Photos WHERE caption LIKE '%".$captiontext."%'";
            }
            //radio button "exact" is selected
            else{
                $query = "SELECT image_url FROM Photos WHERE caption LIKE '".$captiontext."'";
            }
            $photomatch = $mysqli->query($query);
            $photos = $photomatch->fetch_row();
            
            //no matches found
            if(empty($photos)){
                print("<p class='message'>No matches found for your search! Please search again!</p>");
            }
            //a match is found
            else{
                print("<p class='message'>Below are the photos matching your search results.<br/>");
                print("Click on a thumbnail to view a photo larger or to edit the photo!</p>");

                //print the photo thumbnails into a table 
                print("<table class=albumgrid>");
                print("<tr>");
                //print the first row
                print("<td>");
                $img = $photos[0];
                print("<a href='updatephoto.php?image=$img' target='_blank'>
                      <img src='img/$img' alt='$img'/></a>");
                print("</td>");
                
                //print the rest of the array
                $count = 2;
                while($photos = $photomatch->fetch_assoc()){
                    $img = $photos['URL'];
                    //third picture of the row
                    if($count == 3){
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'>
                              <img src='img/$img' alt='$img'/></a>");
                        print("</td>");
                        print("</tr>");
                        $count = 1;
                    }
                    //first picture of the row
                    elseif($count == 1){
                        print("<tr>");
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'>
                              <img src='img/$img' alt='$img'/></a>");
                        print("</td>");
                        $count = 2;
                        
                    }
                    //second picture of the row
                    else{
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'>
                              <img src='img/$img' alt='$img'/></a>");               
                        print("</td>");
                        $count = 3;
                    }
                }
                print("</table>");
            
            }
        }
        
        //no search entry, display ALL photos
        else{
            print("<p class='message'>Below are all the photos currently in our database.<br/>");
            print("Click on a thumbnail to view a photo larger or to edit the photo!</p>");
            
            $query = "SELECT * FROM Photos";
            $photoarray = $mysqli->query($query);
            $photos = $photoarray->fetch_assoc();
            
            //no photos in database at all
            if(empty($photos)){
                print("<p>There are currently no photos in our archives. Click <a href='add.php'>here</a> to add photos!</p>");
            }
            
            //print the photo thumbnails into a table 
            else{
                print("<table class=albumgrid>");
                print("<tr>");
                //print the first row
                print("<td>");
                $img = $photos['image_url'];
                print("<a href='updatephoto.php?image=$img' target='_blank'>
                      <img src='img/$img' alt='$img'/></a>");
                print("</td>");
                
                //print the rest of the array
                $count = 2;
                while($photos = $photoarray->fetch_assoc()){
                    $img = $photos['image_url'];
                    //third picture of the row
                    if($count == 3){
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'>
                              <img src='img/$img' alt='$img'/></a>");
                        print("</td>");
                        print("</tr>");
                        $count = 1;
                    }
                    //first picture of the row
                    elseif($count == 1){
                        print("<tr>");
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'>
                              <img src='img/$img' alt='$img'/></a>");
                        print("</td>");
                        $count = 2;
                        
                    }
                    //second picture of the row
                    else{
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'>
                              <img src='img/$img' alt='$img'/></a>");               
                        print("</td>");
                        $count = 3;
                    }
                }
                print("</table>");
            
            }
        }
        }
        $mysqli->close();
        
        
        ?>
	</div>
    </body>
    </html>

