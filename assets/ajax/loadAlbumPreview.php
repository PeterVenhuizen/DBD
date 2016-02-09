<?php
    require_once('../config.php');

    if (isset($_POST['album_year'])) { 

        $year = mysql_real_escape_string($_POST['album_year']);

        if (!empty($_SESSION['user'])) { $year_query = "SELECT * FROM photo_albums WHERE YEAR(date) = $year ORDER BY date DESC"; } 
        else { $year_query = "SELECT * FROM photo_albums WHERE YEAR(date) = $year AND private = 0 ORDER BY date DESC"; }

        $ext_allowed = array("jpg", "jpeg", "gif", "png");
        $forbidden = array(".", "..", ".DS_Store");

        try {
            $stmt = $db->prepare($year_query);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                foreach ($stmt as $album) {
                    $path = '../../images/' . $album['name'] . '/';
                    if ($handle = opendir($path)) {
                        while (false !== ($entry = readdir($handle))) {
                            $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                            if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
                                list($w, $h) = getimagesize($path.$entry);
                                if ($h < $w) { $class = 'thumb_landscape'; } 
                                else { $class = 'thumb_portrait'; }
                                echo '	<!--<a href="photo_browser.php?album=' . $album['name'] . '">-->
                                        <a href="Fotos/' . $album['name'] . '/">
                                            <div class="album_preview">
                                                <h5 id="' . $album['name'] . '">' . str_replace('_', ' ', $album['name']) . '</h5>
                                                <img class="photo-thumb ' . $class . '" src="images/' . $album['name'] . '/' . $entry . '" />
                                            </div>
                                        </a>';
                                break;
                            } 
                        }
                    }   
                }
            }
        } catch (PDOException $ex) { die(); }
    }
?>