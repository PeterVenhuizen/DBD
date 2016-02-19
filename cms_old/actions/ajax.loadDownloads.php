<?php

	require_once('../../assets/config.php'); 

	$path = $_SERVER[DOCUMENT_ROOT] . '/downloads/';
	$forbidden = array(".", "..", ".DS_Store");

	if (isset($_POST['delete_file'])) {
		unlink($path . $_POST['delete_file']);
	}

	if ($handle = opendir($path)) {
		while (false !== ($entry = readdir($handle))) {
			$extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
			if (!in_array($extension, array('jpg', 'jpeg', 'png', 'gif', 'zip', 'xls', 'xlsx', 'doc', 'docx', 'pdf', 'mp3', 'zip'))) { $extension = 'unknown'; }						
			if (!in_array($entry, $forbidden)) {
				echo '	<li class="download ' . $extension . '">' . substr($entry, 0, 30) . '<span id="' . $entry . '" class="delete_download" title="Verwijder bestand"></span></li>';
			} 
		}
	}
	closedir($handle);
?>
