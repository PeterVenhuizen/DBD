<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Foto's</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">       
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>    
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>        
		<script type="text/javascript" src="assets/js/general.js"></script>
        <script type="text/javascript" src="assets/js/photos.js"></script>
        <!--[if IE]>
        <script>
            $(document).ready(function () {
                document.createElement('main');
            });
        </script>
        <![endif]-->          
		<meta name="viewport" content="initial-scale=1">
	</head>  
    
	<body>         
           
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.0";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>  
        
		<?php include_once("analyticstracking.php"); ?>        
        
        <?php
            $album = '';
            if (isset($_GET['album'])) {
                $album = mysql_real_escape_string($_GET['album']);
        ?>        
            
            <!-- Photo menu -->
            <div id="photo_menu">
                <a href="index.php"><img src="assets/img/dbd_logo_100.png"></a>
                <a href="photos.php"><img src="assets/img/back_icon.png"></a>
                <h1 id="album_name" name="<?php echo $album; ?>"><?php echo str_replace('_', ' ', $album); ?></h1>
            </div>
            
            <!-- Photo browser wrapper -->
            <div id="photo_browser_content">
                
                <!-- Photo browser controls -->
                <div id="photo_browser_controls">
                    <img src="assets/img/play_prev_red.png" id="play_previous">
                    <div id="pause_play" class="pause"></div>
                    <img src="assets/img/play_next_red.png" id="play_next">
                    <img src="assets/img/save_image_icon.png" id="save_image">
                </div>
            
        <?php
                
                // Check if the album exists in database
                try { 
                    $stmt = $db->prepare("SELECT * FROM photo_albums WHERE name = :album");
                    $stmt->execute(array(':album' => $album));
                    
                    if ($stmt->rowCount() > 0) {
                        
                        // Photo browser variables
                        $ext_allowed = array("jpg", "jpeg", "gif", "png");
				        $forbidden = array(".", "..", ".DS_Store");
				        $path = 'images/' . $album . '/';
                        
                        // Pictures array
                        $files = array();
                        if ($handle = opendir($path)) {                        
                            
                            while (false !== ($entry = readdir($handle))) {
                                $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                                if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
                                    $files[] = $path . $entry;   
                                }
                            }
                            
                            closedir($handle);
                            
                            // Sort pictures on name
                            natsort($files);
                            
                            // Iterate over all pictures in array
                            echo '  <div id="dia_show">
                                        <div id="dia-show-photos">
                                            <photo-container class="start"></photo-container>';
                            
                            foreach ($files as $picture) {
                                list($w, $h) = getimagesize($picture);
                                if ($h < $w) { echo "  <photo-container><img class='photo landscape' src=" . $picture . "></photo-container>\n"; }
                                else { echo " <photo-container><img class='photo portrait' src=" . $picture . "></photo-container>\n"; }
                            }
                            
                            echo '          <photo-container class="end"></photo-container>
                                        </div>
                                    </div>';
                            
                            // Display the thumbnails
                            echo '	<ul id="thumb-rail">';

                            foreach ($files as $picture) {
                                list($w, $h) = getimagesize($picture);
                                if ($h < $w) { echo "	<li><img class='thumb thumb_landscape' src=" . $picture . " /></li>\n"; } // Landscape 
                                else { echo " 	<li><img class='thumb thumb_portrait' src="  . $picture . " /></li>\n"; } // Portrait
                            }	

                            echo '	</ul>';                       
                            
                        } 
                        
                    } else {
                        echo '<h1 id="album_not_found">Album ' . $album . ' niet gevonden!</h1>';
                    }
                    
                } catch (PDOException $ex) { die(); }
     
                echo '</div>';            
                
            } else {
                echo '<h1 id="album_not_found">Album ' . $album . ' niet gevonden!</h1>';
            }
        ?>
                
        <?php include('footer.php'); ?>        
		
	</body>
</html>
