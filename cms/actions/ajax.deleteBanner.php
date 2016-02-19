<?php
	require 'functions.php';
    if (isset($_POST['filename'])) {
        
        $path = $_SERVER[DOCUMENT_ROOT] . '/assets/img/photo_banner/';
        unlink($path . $_POST['filename']);
        
		// Save to log
		add_to_log($db, array('user' => $_SESSION['user']['email'], 'action' => 'DELETE', 'page' => 'cms.banners.php', 'desc' => $_POST['filename']));
        
    }
?>
