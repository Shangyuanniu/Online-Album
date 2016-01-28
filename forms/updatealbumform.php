<form class="form" action="updatealbum.php" method="post">
    <h1>Update Album: <?php print($album); ?></h1>
    <p>album title: <input type="text" name="albumtitle" /></p>
    <p><input type="submit" name="update" value="Update Album" /></p>
    <hr>
    <h3>Delete Album</h3>
    <p>Click to delete this album from the archives. Note that the photos in this album will not be deleted, and can still be
    viewed from the Photos page!</p>
    <p><input type="submit" name="delete" value="Delete Album" /></p>
</form>