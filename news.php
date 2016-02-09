<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Nieuws</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">
        <?php
            if (isset($_GET['title'])) {
                $title = $_GET['title'];
                $title = str_replace("_"," ", $title);
                echo '<meta property="og:title" content="' . $title . '" />';
            }
        ?>
        <meta property="og:image" content="assets/img/dbd_logo_300.png" />
        <meta property="og:type" content="article" />
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
        <script type="text/javascript" src="assets/js/sidebar.js"></script>
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
            
		<?php
		
			if (isset($_GET['id']) AND isset($_GET['title'])) {
				
				try {
					$stmt = $db->prepare("SELECT * FROM articles WHERE id = :id LIMIT 1");
					$stmt->execute(array(':id' => mysql_real_escape_string($_GET['id'])));
				} catch(PDOException $ex) { die(); }
				
				if ($stmt->rowCount() > 0) {
					$row = $stmt->fetch();
					echo '	<article class="news">
								<header><h2>' . $row['title'] . '</h2></header>
								<p>' . html_entity_decode($row['content']) . '</p>
                                <footer>
                                    <!--<div class="fb-like" data-href="' . 'Nieuws/' . $row['id'] . '/' . str_replace(' ', '_', $row['title']) . '" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>-->
                                    <div class="fb-like" data-href="' . 'Nieuws/' . $row['id'] . '/' . str_replace(' ', '_', $row['title']) . '" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
                                </footer>
							</article>';
				} else {
					echo '	<article>
								<header><h2>Artikel niet gevonden!</h2></header>
								<p>Oops! Iets is er mis gegaan, het lijkt erop dat dit artikel niet (meer) bestaat! Sorry voor het ongemak!</p>
							</article>';
				}
                
				// Latest news
				try {
					$stmt = $db->prepare("SELECT * FROM articles WHERE published AND id != :id AND category != 'veluwecup' AND category != 'gcup' ORDER BY pub_date DESC LIMIT 3");
					$stmt->execute(array(':id' => mysql_real_escape_string($_GET['id'])));
				} catch(PDOException $ex) { die(); }
				
				if ($stmt->rowCount() > 0) {
					$latestNews = '<div id="latest_news"><h2>Laatste nieuws</h2>';
					foreach ($stmt as $row) {
					
						// Limit the number of words for front page posts
						$words = explode(" ", $row['content']);
						$first_30 = implode(" ", array_splice($words, 0, 30)) . ' ... ';					
					
						$latestNews .= '<div class="small_news"><h3><a href="' . 'Nieuws/' . $row['id'] . '/' . str_replace(' ', '_', $row['title']) . '">' . $row['title'] . '</a></h3><p class="short">' . html_entity_decode($first_30) . '</p></div>';
					}
					
					echo $latestNews . '</div>';
				}
				
			} else {
				echo '	<article>
							<header><h2>Artikel niet gevonden!</h2></header>
							<p>Oops! Iets is er mis gegaan, het lijkt erop dat dit artikel niet (meer) bestaat! Sorry voor het ongemak!</p>
						<article>';
			}
			
		?>           
			
            
        <?php include('sidebar.php'); ?>            
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
