<?php 
	require_once('../../assets/config.php');
	require 'functions.php';

    try {
        $stmt = $db->prepare("UPDATE banners SET title = :title, description = :desc, url = :url, url_text = :url_text, img = :img WHERE id = :id");
        $stmt->execute(array(':title' => $_POST['title'], ':desc' => $_POST['desc'], ':url' => $_POST['url'], ':url_text' => $_POST['url_text'], ':img' => $_POST['img'], ':id' => $_POST['id']));        
    } catch (PDOException $ex) { die(); }
					
	// Save to log
	add_to_log($db, array('user' => $_SESSION['user']['email'], 'action' => 'EDIT', 'page' => 'cms.banners.php', 'desc' => $_POST['title']));

?>
