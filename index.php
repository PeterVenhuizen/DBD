<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin</title>
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
        <script type="text/javascript" src="assets/js/photo_banner.js"></script>
        <!--[if IE]>
        <script>
            $(document).ready(function () {
                $('article.news img').each(function () {
                    $(this).width(600);
                    $(this).height(auto);
                });
                document.createElement('main');
            });
        </script>
        <style>
            @media screen and (min-width: 1025px) {
                #sidebar { margin-top: 375px; }
            }
        </style>
        <![endif]-->        
        <script type="text/javascript">
            // Adjust sidebar top offset to avoid photo_banner
            $(document).ready(function () {
                
                var jump_sidebar = function () {
                     if ($(window).width() > 1024) {
                        $('#sidebar').css({"margin-top": 375});   
                    } else {
                        $('#sidebar').css({"margin-top": 0});   
                    }
                };
                
                jump_sidebar();
                
                $(window).resize(function () {
                    jump_sidebar();
                });
            });
        </script>
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
            
            <!-- PHOTO BANNER -->
            <section id="photo_banner">
                <ul class='slideshow'>
                <?php
                    try {
                        $stmt = $db->prepare("SELECT * FROM banners WHERE banner_type = 'home_banner' ORDER BY id LIMIT 3");
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            foreach ($stmt as $row) {
                                echo '  <li>
                                            <img src="assets/img/photo_banner/' . $row['img'] . '">
                                            <a href="' . $row['url'] . '" alt="' . $row['url_text'] . '"></a>
                                            <span class="title">' . $row['title'] . '</span>
                                            <span class="description">' . $row['description'] . '</span>
                                        </li>';
                            }
                        }
                    } catch (PDOException $ex) { die(); }
                ?>
                </ul>
                <ul id='banner_dots'>
                    <li class="filled"></li>
                    <li></li>
                    <li></li>
                </ul>
                <div id='banner_box'>
                    <h1></h1>
                    <p></p>
                    <a></a>
                </div>
            </section>             
            
		<?php
		
			// Retrieve sticky posts from DB
            try {
                $stmt = $db->prepare("SELECT * FROM articles WHERE published AND sticky AND category != 'veluwecup' AND category != 'gcup' ORDER BY pub_date DESC LIMIT 3");
                $stmt->execute();
            } catch (PDOException $ex) { die(); }
            if ($stmt->rowCount() > 0) {
                $postEcho = '';
                foreach ($stmt as $sticky) {
				
                    // Limit the number of words for front page posts
                    $maxWords = 60; 
                    $words = explode(" ", $sticky['content']);
                    $first_60 = implode(" ", array_splice($words, 0, $maxWords)) . ' ... ';

                    $postEcho .= '<article class="news">
                                        <header><h2><a href="' . 'Nieuws/' . $sticky['id'] . '/' . str_replace(' ', '_', $sticky['title']) . '">' . $sticky['title'] . '</a></h2></header>
                                        <p class="short">' .  html_entity_decode($first_60) . '<a href="' . 'Nieuws/' . $sticky['id'] . '/' . str_replace(' ', '_', $sticky['title']) . '" class="a_more">Lees meer</a></p>
                                    </article>';
                }
			}
			
			// Retrieve the last 3 posts
            try {
                $stmt = $db->prepare("SELECT * FROM articles WHERE published AND NOT sticky AND category != 'veluwecup' AND category != 'gcup' ORDER BY pub_date DESC LIMIT 3");
                $stmt->execute();
            } catch (PDOException $ex) { die(); }
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
        
        <article class="archive news">
            <p>Lees meer in ons <a href="Nieuwsarchief/">nieuwsarchief</a>.</p>    
        </article>    
            
        <?php include('sidebar.php'); ?>
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
