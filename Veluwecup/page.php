<?php require_once('../assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">
		<link rel="stylesheet" href="assets/css/veluwecup.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/veluwecup.js"></script>
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
		<?php
		
            // Retrieve the subpage based on the parent menu 
            // item name and the subpage name
            if (isset($_GET['parentName']) AND isset($_GET['pageName'])) {
                
                // Get the parent_id
                try {
                    $parentname = str_replace('_', ' ', mysql_real_escape_string($_GET['parentName']));
                    $stmt = $db->prepare("SELECT * FROM vcup_menu WHERE name = :parentName LIMIT 1");
                    $stmt->bindValue(':parentName', $parentname);
                    $stmt->execute();
                } catch(PDOException $ex) { die(); }
                
                // Check if the parentId was found;
                if ($stmt->rowCount() > 0) {
                    $parentId = $stmt->fetchColumn(); // Get the id
                    
                    // Get the subpage based on the parentId
                    // and the subpage name
                    try {
                        $pageName = str_replace('_', ' ', mysql_real_escape_string($_GET['pageName']));
                        $stmt = $db->prepare("SELECT * FROM vcup_subpages WHERE parent_id = :parentId AND name = :pageName LIMIT 1");
                        $stmt->execute(array(':parentId' => $parentId, ':pageName' => $pageName));
                    } catch(PDOException $ex) { die(); }
                    
                    // Check if the page was found
                    if ($stmt->rowCount() > 0) {
                        $result = $stmt->fetch();
                        echo '  <article>
                                    <header><h2>' . $result['name'] . '</h2></header>
                                    <p>' . html_entity_decode($result['content']) . '</p>
                                </article>';
                    } else { 
                        echo '	<article class="news">
                                    <header><h2>Pagina niet gevonden!</h2></header>
								    <p>Oops! Er is iets mis gegaan, het lijkt erop dat dit artikel niet (meer) bestaat! Sorry voor het ongemak!</p>
							     </article>'; 
                    }
                    
                } else { // If not, display page not found
                    echo '	<article class="news">
								<header><h2>Pagina niet gevonden!</h2></header>
								<p>Oops! Er is iets mis gegaan, het lijkt erop dat dit artikel niet (meer) bestaat! Sorry voor het ongemak!</p>
							</article>';
                }
            }
                
		?>
			
		</main>
		
		<?php include('../footer.php'); ?>
		
	</body>
</html>
