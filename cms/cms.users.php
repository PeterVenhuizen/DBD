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
		<script type='text/javascript' src='js/users.js'></script>
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
			<button id='btn_download' class='btn_other'>Download ledenlijst</button>
			
            <article class='help'>
                <header>
                    <h2>Leden uitleg</h2>
                </header>
				<p>Hier kan je de rechten van de geregistreerde gebruikers wijzigen. Een standaard gebruiker heeft geen rechten en kan niet inloggen in de CMS.</p>
				<p>0 - Standaard gebruiker</p>
				<p>1 - Gebruiker foto's en downloads uploaden en nieuws en agenda items aanmaken, wijzigen en verwijderen. </p>
				<p>2 - Gebruiker kan competities en wedstrijden aanmaken, wijzigen en verwijderen</p>
				<p>3 - Alle rechten (Admin). Een admin kan de web structuur aanpassen (menu en submenu), nieuwe contacten toevoegen aan de contact pagina en de rechten van andere gebruikers wijzigen.</p>					
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
					try {
						$stmt = $db->prepare('SELECT id, first_name, prefix, last_name, email, rights FROM users ORDER BY first_name');
						$stmt->execute();
						if ($stmt->rowCount() > 0) {
							foreach ($stmt as $user) {
								echo '	<tr>
											<td class="user_name">' . $user['first_name'] . ' ' . $user['prefix'] . ' ' . $user['last_name'] . '</td>
											<td class="user_email">' . $user['email'] . '</td>
											<td class="user_rights">
												<select name="user_rights">
													<option class="user_rights" ' . ($user['rights'] == 0 ? "SELECTED" : "") . ' value="0">0</option>
													<option class="user_rights" ' . ($user['rights'] == 1 ? "SELECTED" : "") . ' value="1">1</option>
													<option class="user_rights" ' . ($user['rights'] == 2 ? "SELECTED" : "") . ' value="2">2</option>
													<option class="user_rights" ' . ($user['rights'] == 3 ? "SELECTED" : "") . ' value="3">3</option>
												</select>
											</td>
											<td>
												<button name="btn_user_rights" class="btn_edit btn_user_rights" value="' . $user['id'] . '"></button>
											</td>
											<td>
												<button name="btn_user_delete" class="btn_delete btn_user_delete" value="' . $user['id'] . '"></button>
											</td>
										</tr>';
							}
						}
					} catch (PDOException $ex) { }
				?>
			</table>
            
        </main>
        
        <?php 
        	}
        	include('cms.footer.php'); 
        ?>
        
    </body>
    
</html>
