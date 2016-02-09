<?php require_once('../assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Veluwecup</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">
		<link rel="stylesheet" href="assets/css/veluwecup.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
        <script src="assets/js/veluwecup.js"></script>
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

		<?php include_once("../analyticstracking.php"); ?>                
        
		<?php include('header.php'); ?>       
        
		<main>
            
            <!-- PHOTO BANNER -->
            <section id="photo_banner">
                <?php
                    try {
                        $stmt = $db->prepare("SELECT img FROM banners WHERE banner_type = 'vcup_banner' LIMIT 1");
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            $row = $stmt->fetch();
                            echo '  <img src="assets/img/photo_banner/' . $row['img'] . '">';
                        }
                    } catch (PDOException $ex) { die(); }
                ?>
            </section>             
            
		<?php
			
			// Retrieve the last 3 posts
            try {
                $stmt = $db->prepare("SELECT * FROM articles WHERE published AND category = 'veluwecup' OR category = 'gcup' ORDER BY pub_date DESC LIMIT 3");
                $stmt->execute();
            } catch (PDOException $ex) { die(); }
            if ($stmt->rowCount() > 0) {
                $postEcho = '';
                foreach ($stmt as $post) {   
				
                    // Again limit the max number of words
                    $maxWords = 60; 
                    $words = explode(" ", $post['content']);
                    $first_60 = implode(" ", array_splice($words, 0, $maxWords)) . ' ... ';
                    $postEcho .= '<article>
                                        <header><h2><a href="' . 'Veluwecup/Nieuws/' . $post['id'] . '/' . str_replace(" ","_", $post['title']) . '">' . $post['title'] . '</a></h2></header>
                                        <p class="short">' .  html_entity_decode($first_60) . '<a href="' . 'Veluwecup/Nieuws/' . $post['id'] . '/' . str_replace(" ","_", $post['title']) . '" class="a_more">Lees meer</a></p>
                                    </article>';	
                }
			}
			
			echo $postEcho;		
		
		?>
            
		</main>
		
		<?php include('../footer.php'); ?>
		
	</body>
</html>
