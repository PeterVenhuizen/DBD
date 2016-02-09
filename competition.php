<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">     
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
        <script type="text/javascript" src="assets/js/competition.js"></script>
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

            <div id="competitionWrapper">
                
                <?php
                    // Load the teams from database
                    $team_selection = '<label>Selecteer team: </label><select id="select_team">';
                    try {
                        $stmt = $db->prepare("SELECT * FROM teams");
                        $stmt->execute();
                    } catch (PDOException $ex) { die("Failed to run query: " . $ex->getMessage()); }

                    if ($stmt->rowCount() > 0) {
                        foreach ($stmt as $team) {
                            $team_selection .= '<option value="' . $team['team_id'] . '">' . $team['team_name'] . '</option>';
                        }
                        echo $team_selection . '</select>';
                    }
                ?>
                
                <div id="ajax_competition_content"><!-- The team competition info will be loaded here --></div>
			</div>                
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
