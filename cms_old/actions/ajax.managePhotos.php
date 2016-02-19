<?php 
	require_once('../../assets/config.php');

	if (isset($_POST['album_name'])) {
	
		echo '	<a name="a_manage_album"></a>
				<h2 id="browse_album_name">' . str_replace('_', ' ', $_POST['album_name']) . '</h2>
				<button id="btn_delete_album" value="' . $_POST['album_name'] . '">Verwijder album</button>
				<p id="manage_album_description">Via deze weergave kun je het geselecteerde fotoalbum verwijderen, of desgewenst specifieke foto\'s verwijderen door op deze te klikken.</p>';
	
		$path = '../../images/'.$_POST['album_name'].'/';
		$ext_allowed = array("jpg", "jpeg", "gif", "png");
		if ($handle = opendir($path)) {
			while (false !== ($entry = readdir($handle))) {
				$extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
				if (in_array($extension, $ext_allowed)) {
					list($w, $h) = getimagesize($path.$entry);
					if ($h < $w) { $class = 'landscape'; } 
					else { $class = 'portrait'; }
					echo '	<img src="' . $path . $entry . '" id="' . $entry . '" class="img_delete ' . $class . '"/>';
				}
			}
		}	
	} else if ($_POST['delete_album']) {
	
		#Delete directory and files
		function delete_files($target) {
			if(is_dir($target)){
				$files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
		
				foreach( $files as $file ) {
					delete_files( $file );      
				}
	  
				rmdir( $target );
			} elseif(is_file($target)) {
				unlink( $target );  
			}
		}	
		$album = $_POST['delete_album'];
		$path = '../../images/' . $album . '/';
		delete_files($path);
		
		#Delete entry in database
		$query = mysql_query("DELETE FROM photo_albums WHERE name = '$album'");
	} else if (isset($_POST['delete_photo']) AND isset($_POST['album'])) {
		$name = $_POST['delete_photo'];
		$album = $_POST['album'];
		$path = '../../images/' . $album . '/' . $name;
		unlink($path);
	}
?>