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
		<!--add an album-->
		
		
		<?php
		 if(isset($_SESSION['user'])){
		require('forms/addalbumform.php');
		//check whether the form is submitted correctly
		if(!empty($_POST['albumname'])){
		       $name = strip_tags($_POST['albumname']);		   
		       //connect to the database to add the new album
		       $mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
		       if($mysqli->errno){
			   print($mysqli->error);
			   exit();
		       }
		       else{
			   //check that the album name does not already exist
			   $query = "SELECT * FROM Albums WHERE title LIKE '".$name."'";
			   $albumtitle = $mysqli->query($query);
			   $titlearray = $albumtitle->fetch_row();
			   
			   //album already exists
			   if(count($titlearray) != 0){
			       print("<p class='error'>An album named $name already exists!
				     Please choose a different name for your new album.</p>");
			   }
			   
			   //album doesn't exist, add the album to the archive
			   else{
			       $query = "INSERT INTO Albums(title, date_created, date_modified) VALUES('".$name."', NOW(), NOW())";
			       $insert = $mysqli->query($query);
			       if($insert){
				   print("<p class='message'>$name has been successfully added to the archive!</p>");
			       }
			       else{
				   print("<p class='error'>An error has occurred. Please try again.</p>");
			       }
			       $mysqli->close();
			   }
		       }
		   }
		   //form is submitted without being completed
		   elseif(isset($_POST['submit'])){
		       print("<p class='error'>please name the new album before submitting!</p>");
		   }
		 
	?>
	<div class="content">
	    <table class=albumgrid>
			<?php
	$mysqli = new mysqli("localhost", "sn522sp15", "nsy81998050", "info230_SP15_sn522sp15");
	$query = "SELECT * FROM Albums";
	
        
        //print album information onto table
            $table = $mysqli->query($query);
            while ($array = $table->fetch_assoc()) {
                print("<tr>");
                
                //print album title
                $title = $array['title'];
                
                print("<td class ='album'><a href='viewalbum.php?album=$title'>".$title."</a></td>");
                
                //print date created
                print("<td class='album'>Created on ".substr($array['date_created'], 0, 10)."</td>");
                
                //print time last modified
                print("<td class='album'>Last modified on ".substr($array['date_modified'], 0, 10)."</td>");
		
		print("<td class='album'><a href='updatealbum.php?album=$title'>Edit</a></td>");
            print("</tr>");
            }
            $mysqli->close();
		 }
		 else{
		    print("<p class='message'>You are not logged in! Please first log in <a href='login.php'>here</a></p>.");
		 }
        
	
		?>
	    
		
		    
	        </table> 
		
	    </div>
	</div>
    </body>
    </html>

