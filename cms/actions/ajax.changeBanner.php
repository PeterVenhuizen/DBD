<?php 
	require_once('../../assets/config.php');

    $id = mysql_real_escape_string($_POST['id']);
    $title = mysql_real_escape_string($_POST['title']);
    $desc = mysql_real_escape_string($_POST['desc']);
    $url = mysql_real_escape_string($_POST['url']);
    $url_text = mysql_real_escape_string($_POST['url_text']);
    $img = mysql_real_escape_string($_POST['img']);

    try {
        $stmt = $db->prepare("UPDATE banners SET title = :title, description = :desc, url = :url, url_text = :url_text, img = :img WHERE id = :id");
        $stmt->execute(array(':title' => $title, ':desc' => $desc, ':url' => $url, ':url_text' => $url_text, ':img' => $img, ':id' => $id));        
    } catch (PDOException $ex) { die(); }

?>