<?php
require_once 'db_handle.php';

session_start();

if ( isset($_POST['albumId']) ) {
	$album_id = $_POST['albumId'];
	$conn = new Dbase();
	$json = $conn->get_gallery($album_id);
	echo $json;
}
?>