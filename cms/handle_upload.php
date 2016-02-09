<?php

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	#$path = $_SERVER[DOCUMENT_ROOT] . '/';
	$path = $_SERVER[DOCUMENT_ROOT] . 'DBD/';

	if ($_GET['name'] == 'download') {
		$path .= 'downloads/';
		$allowed = array('png', 'jpg', 'jpeg', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'zip');		
	} else if ($_GET['name'] == 'banner') {
		$path .= 'assets/img/photo_banner/';
		$allowed = array('png', 'jpg', 'jpeg', 'gif');
	} else if ($_GET['name'] == 'newsImages') {
		$path .= 'images/newsImages/';	
		$allowed = array('png', 'jpg', 'jpeg', 'gif');
	} else {
		$path .= 'images/' . $_GET['name'] . '/';
		$allowed = array('png', 'jpg', 'jpeg', 'gif');
	}

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES['upl']['tmp_name'], $path.$_FILES['upl']['name'])){
		
		//Resize image if it will go to a photo album
		if ($_GET['name'] != 'download' && $_GET['name'] != 'newsImages' && $_GET['name'] != 'banner') {
			include_once('img_resize.php');
			$target_file = $path.$_FILES['upl']['name'];
			$resized_file = $path.$_FILES['upl']['name'];
			img_resize($target_file, $resized_file, 800, 600, $extension);
		}
		
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;
