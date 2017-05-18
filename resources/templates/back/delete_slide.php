<?php

require_once("../../resources/config.php");

if(isset($_GET['delete_slide_id'])){

	// find image_name by id, and unlink (truly delete) image in uploads folder
	$query_find_image = query("SELECT slide_image FROM slides WHERE slide_id = " . escape_string($_GET['delete_slide_id']) . " LIMIT 1 ");
	confirm($query_find_image);	
  	$row = fetch_array($query_find_image);
	$target_path = UPLOAD_DIRECTORY . DS . $row['slide_image'];
	unlink($target_path);


	// delete image in phpmyadmin
	$query = query("DELETE FROM slides WHERE slide_id = " . escape_string($_GET['delete_slide_id']) . " ");
	confirm($query);


	set_message("Slide Deleted");

	redirect("index.php?slides");

} else{

	redirect("index.php?slides");

}

?>

