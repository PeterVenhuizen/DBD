<?php 
	require_once('../../assets/config.php');

	# Update current competition
	// Create new photo album folder
	if (isset($_POST['album_name']) && isset($_POST['visibility']) && isset($_POST['album_date'])) {

		$path = $_SERVER[DOCUMENT_ROOT] . '/images/';

		// Add the new album to the mysql database
		$query = "INSERT INTO photo_albums (name, private, date) VALUES (:name, :private, :date)";
		$mysql_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-',$_POST['album_date'])));
		$query_params = array(':name' => $_POST['album_name'], ':private' => $_POST['visibility'], ':date' => $mysql_date);	
		try { 
			$stmt = $db->prepare($query); 
			$result = $stmt->execute($query_params); 
		} catch(PDOException $ex) { 
			// Note: On a production website, you should not output $ex->getMessage(). 
			// It may provide an attacker with helpful information about your code.  
			die(); 
		} 		

		// Create the album on the server
		$folder_name = $_POST['album_name'] . '/';
		if (!file_exists($path . $folder_name)) {
			mkdir($path . $folder_name, 0777, true);
		}

	}

?>