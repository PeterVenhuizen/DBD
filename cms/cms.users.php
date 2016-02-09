<!DOCTYPE html>

<?php require_once('../assets/config.php'); ?>

<html>

	<head>
		<meta charset='UTF-8'>
		<title>CMS - Leden</title>
		<link rel='stylesheet' type='text/css' href='../assets/css/cms_responsive.css'>
		<link rel='icon' href='img/logo_small.png'>
		<!--[if IE]><link rel="shortcut icon" href="img/logo_small.ico"><![endif]-->
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script type='text/javascript' src='js/general.js'></script>
		<meta name="viewport" content="initial-scale=1">
	</head>
    
    <body>
        
        <?php 
        	include('cms.menu.php'); 
        	
        	if (empty($_SESSION['user'])) {
        		header("Location: ../cms/cms.login.php");
        		die();
        	}
        	
        	if ($_SESSION['user']['rights'] < 3) {
        		echo 'Je beschikt niet over de vereiste gebruikersrechten om deze pagina te zien! Voor vragen neem contact op met de <a href="www.debalderin.wur.nl/Contact/">Admin</a>';
        	} else {
        ?>
        
        <main>
        
            <img class='img_get_help' src='../assets/img/whats_this.PNG' alt='Help'>
            
            <article class='help'>
                <header>
                    <h2>Activiteiten uitleg</h2>
                </header>
                <p>Manage activiteiten voor Debbie's Hangout. Geregistreerde leden kunnen zich via Debbie's Hangout aanmelden. Geef iedere activiteit een (unieke) naam, datum en beschrijving en bepaal vervolgens of de activiteit open is voor inschrijving (is online ja of nee). Maak gebruik van de <a href="http://www.w3schools.com/tags/tag_br.asp" target="blank">br-tag</a> voor het invoeren van witregels.</p>
            </article>
            
			<table id='users'>
				<tr>
					<th class="user_name">Naam</th>
					<th class="user_email">Email</th>
					<th class="user_rights">Rechten</th>
					<th></th>
					<th></th>
				</tr>
				<?php
					$users = mysql_query('SELECT id, first_name, prefix, last_name, email, rights FROM users ORDER BY first_name');
					while ($user = mysql_fetch_assoc($users)) {
						echo '	<tr>
									<td class="user_name">' . $user['first_name'] . ' ' . $user['prefix'] . ' ' . $user['last_name'] . '</td>
									<td class="user_email">' . $user['email'] . '</td>
									<td class="user_rights"></td>
								</tr>';
					}
				?>
			</table>
            
        </main>
        
        <?php 
        	}
        	include('cms.footer.php'); 
        ?>
        
    </body>
    
</html>
