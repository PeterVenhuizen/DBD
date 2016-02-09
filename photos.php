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
        }(document, 'script', 'facebook-jssdk'));</script> 
		
		<?php include_once("analyticstracking.php"); ?>        
        
        <?php include('header.php'); ?>       
        
		<main>

            <div id="album_years">
            <?php
                
                // Get the photo albums years out of the database
                // Determine whether the user is logged in or not
                if (!empty($_SESSION['user'])) { $album_query = "SELECT YEAR(date) AS year_album FROM photo_albums GROUP BY year_album ORDER BY year_album DESC"; }
                else { $album_query = "SELECT YEAR(date) AS year_album FROM photo_albums WHERE private = 0 GROUP BY year_album ORDER BY year_album DESC"; }
                
                // Iterate over the database
                try {
                    $stmt = $db->prepare($album_query);
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        foreach ($stmt as $album) {
                            echo '<h3 class="year">' . $album['year_album'] . '</h3>';
                        }
                    }
                } catch (PDOException $ex) { die(); }

            ?>
            </div>
            
            <!-- The previews will go here -->
            <div id="photo-preview-section"></div>
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
