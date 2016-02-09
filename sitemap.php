<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Sitemap</title>
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
  
            <nav id="sitemap">
                            
                <h2>Sitemap</h2>
                
                <?php
                    // Get the menu from database
                    $menuEcho = '<ul>';

                    // Retrieve the major menu items
                    try {
                        $stmt = $db->prepare("SELECT * FROM menu ORDER BY _order");
                        $stmt->execute();
                    } catch (PDOException $ex) { die(); }

                    // Iterate over menu items
                    if ($stmt->rowCount() > 0) {
                        foreach($stmt as $row) {
                            // Check if menu contains a submenu
                            if ($row['expand']) { // There is a submenu
                                // Add the top level menu item
                                $menuEcho .= '<li>' . $row['name'];
                                try {
                                    $sub_stmt = $db->prepare("SELECT * FROM subpages WHERE parent_id = :parentId AND published ORDER BY _order");
                                    $sub_stmt->bindValue('parentId', $row['id']);
                                    $sub_stmt->execute();
                                } catch (PDOException $ex) { die(); }
                                if ($sub_stmt->rowCount() > 0) {
                                    $menuEcho .= '<ul>';
                                    foreach($sub_stmt as $sub) {
                                        $menuEcho .= '<li><a href="' . $row['name'] . '/' . str_replace(' ', '_', $sub['name']) . '">' . $sub['name'] . '</a></li>';
                                    }
                                    $menuEcho .= '</ul>';
                                }
                                $menuEcho .= '</li>';
                            } else { // No submenu
                                // Check if there is a special URL
                                if (empty($row['url'])){ $menuEcho .= '<li><a href="' . $row['name'] . '">' . $row['name'] . '</a></li>'; }
                                else { $menuEcho .= '<li><a href="' . $row['url'] . '">' . $row['name'] . '</a></li>'; }
                            }
                        }
                    }

                    $menuEcho .= '      <li id="hangout"><a href="Leden/">Debbie\'s Hangout<img src="assets/img/debbies_hangout_small.png" id="debbies_hangout" /></a></li>
                                        <li><a href="Nieuwsarchief/">Nieuwsarchief</a></li>
                                        <li><a href="Agenda/">Agenda</a></li>
                                    </ul>
                                    <ul>
                                        <li><a href="Login/">Login</a></li>
                                        <li><a href="Uitloggen/">Uitloggen</a></li>
                                        <li><a href="Registreren/">Registreer</a></li>
                                        <li><a href="Wachtwoord_vergeten/">Wachtwoord vergeten</a></li>
                                    </ul>
                                    <h3>Veluwecup</h3>
                                    <ul>';
                    // Retrieve the major menu items
                    try {
                        $stmt = $db->prepare("SELECT * FROM vcup_menu ORDER BY _order");
                        $stmt->execute();
                    } catch (PDOException $ex) { die(); }

                    // Iterate over menu items
                    if ($stmt->rowCount() > 0) {
                        foreach($stmt as $row) {
                            // Check if menu contains a submenu
                            if ($row['expand']) { // There is a submenu
                                // Add the top level menu item
                                $menuEcho .= '<li>' . $row['name'];
                                try {
                                    $sub_stmt = $db->prepare("SELECT * FROM vcup_subpages WHERE parent_id = :parentId AND published ORDER BY _order");
                                    $sub_stmt->bindValue('parentId', $row['id']);
                                    $sub_stmt->execute();
                                } catch (PDOException $ex) { die(); }
                                if ($sub_stmt->rowCount() > 0) {
                                    $menuEcho .= '<ul>';
                                    foreach($sub_stmt as $sub) {
                                        $menuEcho .= '<li><a href="Veluwecup/' . $row['name'] . '/' . str_replace(' ', '_', $sub['name']) . '/">' . $sub['name'] . '</a></li>';
                                    }
                                    $menuEcho .= '</ul>';
                                }
                                $menuEcho .= '</li>';
                            } else { // No submenu
                                // Check if there is a special URL
                                if (empty($row['url'])){ $menuEcho .= '<li><a href="' . 'Veluwecup/' . $row['name'] . '">' . $row['name'] . '</a></li>'; }
                                else { $menuEcho .= '<li><a href="' . 'Veluwecup/' . $row['url'] . '">' . $row['name'] . '</a></li>'; }
                            }
                        }
                    }

                    echo $menuEcho . '</ul>';
                ?>
            </nav>            
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
