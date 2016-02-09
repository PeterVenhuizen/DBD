<?php
    require_once('../config.php');

    if (isset($_POST['category'])) {
    
        $loadEcho = '';
            
        // Retrieve all posts from database
        try {
            $stmt = $db->prepare("SELECT * FROM articles WHERE published AND category = :cat ORDER BY pub_date DESC");
            if ($_POST['category'] == '*') { $stmt = $db->prepare("SELECT * FROM articles WHERE published ORDER BY pub_date DESC"); }            
            $stmt->execute(array(':cat' => $_POST['category']));
        } catch (PDOException $ex) { die(); }
        if ($stmt->rowCount() > 0) {
            
            $loadEcho .= '<article><h3 id="n_archive_posts">' . $stmt->rowCount() . ' bericht(en) gevonden.</h3></article>';
            
            foreach ($stmt as $post) {   

                // Again limit the max number of words
                $maxWords = 60; 
                $words = explode(" ", $post['content']);
                $first_60 = implode(" ", array_splice($words, 0, $maxWords)) . ' ... ';
                $loadEcho .= '<article class="news">
                                    <header><h2><a href="' . $config['absolute_path'] . 'news.php?id=' . $post['id'] . '&title=' . str_replace(" ","_", $post['title']) . '">' . $post['title'] . '</a></h2></header>
                                    <p class="short">' .  html_entity_decode($first_60) . '<a href="' . $config['absolute_path'] . 'news.php?id=' . $post['id'] . '&title=' . str_replace(" ","_", $post['title']) . '" class="a_more">Lees meer</a></p>
                                </article>';	
            }
        } else {
            $loadEcho .= '  <article class="news">
                                <header><h2>Geen berichten gevonden!</h2></header>
                                <p>Geen berichten gevonden in deze categorie</p>
                            </article>';   
        }

        echo $loadEcho;	
    
    }

?>