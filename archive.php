<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Nieuws Archief</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">      
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
        <!--[if IE]>
        <script>
            $(document).ready(function () {
                document.createElement('main');
            });
        </script>
        <![endif]-->          
		<meta name="viewport" content="initial-scale=1">
        <script>
            $(document).ready(function() {
                var side_height = $('#sidebar').height();
                $('main').css({ "min-height": side_height});                
            });
        </script>
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
            
            <div id="select_category">
                <label>Selecteer categorie: </label>
                <select id="select_news">
                    <option value="*">Alles</option>                    
                    <option value="news">Nieuws</option>
                    <option value="match">Wedstrijdverslagen</option>
                    <option value="tournament">Toernooien</option>
                    <option value="veluwecup">Veluwe cup / G-cup</option>
                </select>
            </div>
            
            <div id="archive_posts">
            
		<?php
    
            $postEcho = '';
			
			// Retrieve all posts from database
            try {
                $stmt = $db->prepare("SELECT * FROM articles WHERE published ORDER BY pub_date DESC");
                $stmt->execute();
            } catch (PDOException $ex) { die("Failed to run query: " . $ex->getMessage()); }
            if ($stmt->rowCount() > 0) {
                foreach ($stmt as $post) {   
				
                    // Again limit the max number of words
                    $maxWords = 60; 
                    $words = explode(" ", $post['content']);
                    $first_60 = implode(" ", array_splice($words, 0, $maxWords)) . ' ... ';
                    $postEcho .= '<article class="news">
                                        <header><h2><a href="' . 'Nieuws/' . $post['id'] . '/' . str_replace(' ', '_', $post['title']) . '">' . $post['title'] . '</a></h2></header>
                                        <p class="short">' .  html_entity_decode($first_60) . '<a href="' . 'Nieuws/' . $post['id'] . '/' . str_replace(' ', '_', $post['title']) . '" class="a_more">Lees meer</a></p>
                                    </article>';	
                }
			}
			
			echo $postEcho;		
		?>
                
            </div>
        
        <?php include('sidebar.php'); ?>
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
