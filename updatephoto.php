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
	      <?php
        
        if(isset($_SESSION['user'])){
            
            //the image that was clicked on
            if(isset($_REQUEST['image'])){
                $image = $_REQUEST['image'];
                $_SESSION['image'] = $image;
                $image = $_SESSION['image'];
            }
            else{
                $image = $_SESSION['image'];
            }
            $mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
            if($mysqli->errno){
                print($mysqli->error);
                exit();
            }
            else{
                $query = "SELECT * FROM Photos WHERE Image_url LIKE '".$image."'";
                $selectedphoto = $mysqli->query($query);
                $selectedphoto = $selectedphoto->fetch_assoc();
                
                //photo has not been deleted
                if(!isset($_POST['delete'])){
                
                //original title and caption of the photo    
                $displayedcaption = $selectedphoto['caption'];
                
                //UPDATE PHOTO
                
                //user clicked "update photo"
                if(isset($_POST['update'])){
                    //keeps track of whether the update was successful
                    $updated = false;
                    
                    
                    //user inputed a caption change
                    if(!empty($_POST['photocaption'])){
                        $newcap = strip_tags($_POST['photocaption']);
                        $query = "UPDATE Photos SET caption = '".$newcap."' WHERE Image_url = '".$image."'";
                        $capupdate = $mysqli->query($query);
                        $updated = $capupdate;
                        if(!$capupdate){
                            print("<p class='error'>The photo caption could not be updated.</p>");
                        }
                        //save new caption to display
                        else{
                            $displayedcaption = $newcap;
                        }
                    }
                    
                    //user inputed a date taken change
                    if(!empty($_POST['year']) || !empty($_POST['month']) || !empty($_POST['day'])){
                        if(empty($_POST['year']) || empty($_POST['month']) || empty($_POST['day'])){
                            print("<p class='error'>To change the date, please input correct values for year,
                                  month, AND day! </p>");
                        }
                        else{
                            $day = $_POST['day'];
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            
                            //format the date to match that of the database
                            $date = "$month $day $year";
                            $newdate = date('Y-m-d', strtotime($date));
                            $query = "UPDATE Photos SET date_taken = '".$newdate."' WHERE Image_url = '".$image."'";
                            $dateupdate = $mysqli->query($query);
                            $updated = $dateupdate;
                            if(!$dateupdate){
                            print("<p class='error'>The photo date could not be updated.</p>");
                            }
                        }
                    }
                    //user checked delete from albums
                    if(!empty($_POST['inalbums'])){
                        $adelete = $_POST['inalbums'];
                        foreach($adelete as $item){
                            $query = "SELECT aID FROM Albums WHERE title LIKE '$item'";
                            $albumid = $mysqli->query($query);
                            $albumid = $albumid->fetch_row();
                            $albumid = $albumid[0];
                            $photoid = $selectedphoto['pID'];
                            
                            $query = "DELETE FROM ImgInAlbum WHERE(aID ='".$albumid."' && pID = '".$photoid."')";
                            $mysqli->query($query);
                            $updated = true;
                        }
                    }
                    
                    //user checked add to albums
                    if(!empty($_POST['addto'])){
                        $add = $_POST['addto'];
                        foreach($add as $item){
                            $query = "SELECT aID FROM Albums WHERE title LIKE '$item'";
                            $albumid = $mysqli->query($query);
                            $albumid = $albumid->fetch_row();
                            $albumid = $albumid[0];
                            $photoid = $selectedphoto['pID'];
                            
                            $query = "INSERT INTO ImgInAlbum VALUES('".$albumid."', '".$photoid."')";
                            $idinserted = $mysqli->query($query);
                            $updated = true;
                        }
                    }
                    //updates were made, print success message
                    if($updated){
                        print("<p class='message'>You have successfully updated this photo!</p>");
                    }
                    require("forms/updatephotoform.php");
                }
                //no submit button clicked
                else{
                    require("forms/updatephotoform.php");
                }
                
                //print the image information
            
                print("<div><img src='img/$image' alt='$image' id='photo' /></div>");
                print("<p class='caption'>$displayedcaption</p>");
                }
        
            else{
                //user clicked "delete photo"
                $pid = $selectedphoto['pID'];
                $query = "DELETE FROM Photos WHERE pID = '".$pid."'";
                $pdeleted = $mysqli->query($query);
                $query = "DELETE FROM ImgInAlbum WHERE pID = '".$pid."'";
                $cdeleted = $mysqli->query($query);
                if($cdeleted && $pdeleted){
                    unlink("img/$image");
                    print("<p class='message'>This photo has been successfully deleted from the archives. <br />
                          go <a href='add.php'>add a photo</a></p>");
                }
                else{
                    print("<p class='error'>A wild error appeared! Please try again!</p>");
                }
            }
            $mysqli->close();
            }
        }
        
        
        else{
            print("<p class='message'>You are not logged in! Please first log in <a href='index.php'>here</a>.</p>");
        }
        ?>
        
	</div>
    </body>
    </html>

