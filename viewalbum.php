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
            //the album that was clicked on
            $album = $_REQUEST['album'];
            
            
            print("<h1>$album</h1>");
            
            $mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
            if($mysqli->errno){
                print($mysqli->error);
                exit();
            }
            
            else{
            $query = "SELECT * FROM Albums WHERE title = '".$album."'";
            $selected = $mysqli->query($query);
            $selected = $selected->fetch_assoc();
            
            
            //display the photos in the selected album
            $aID = $selected['aID'];
            $query = "SELECT Image_url FROM ImgInAlbum NATURAL JOIN Photos WHERE aID LIKE '".$aID."'";
            $photos = $mysqli->query($query);
            $photosarray = $photos->fetch_row();
            
            if(empty($photosarray)){
                print("<p class='empty'>There are currently no photos in this album.
                      Click <a href='add.php'>here</a> to add photos!</p>");
            }
            
            //the albums contain photos, print the photo thumbnails into a table 
            else{
                print("<table class=albumgrid>");
                print("<tr>");
                //print the first row
                print("<td>");
                $img = $photosarray[0];
                print("<a href='updatephoto.php?image=$img' target='_blank'> <img src='img/$img' alt='$img'/></a>");
                print("</td>");
                
                //print the rest of the array
                $count = 2;
                while($photosarray = $photos->fetch_row()){
                    $img = $photosarray[0];
                    //third picture of the row
                    if($count == 3){
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'> <img src='img/$img' alt='$img'/></a>");
                        print("</td>");     
                        print("</tr>");
                        $count = 1;
                    }
                    //first picture of the row
                    elseif($count == 1){
                        print("<tr>");
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'> <img src='img/$img' alt='$img'/></a>");
                        print("</td>");
                        $count = 2;
                        
                    }
                    //second picture of the row
                    else{
                        print("<td>");
                        print("<a href='updatephoto.php?image=$img' target='_blank'> <img src='img/$img' alt='$img'/></a>");               
                        print("</td>");
                        $count = 3;
                    }
                }
                print("</table>");
            }
            $mysqli->close();
            }
	    }
        else{
            print("<p class='message'>You are not logged in! Please first log in <a href='login.php'>here</a>.</p>");
        }
        ?>
		    
	        </table> 
		
	    </div>
	</div>
    </body>
    </html>

