<?php require_once('../assets/config.php'); ?>

<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS</title>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
	</head>
	
	<body>
		<!-- MENU -->
		<?php include('cms.menu.html');	?>
		
<?php 
     
    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) { 
        // If they are not, we redirect them to the login page. 
        header("Location: cms.login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
    
	if ($_SESSION['user']['rights'] < 1) {
    	echo 'Je beschikt niet over de vereiste rechten om deze pagina te bewerken! <a href="cms.logout.php"> Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 
	 
		// We can display the user's username to them by reading it from the session array.  Remember that because 
		// a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 		
		<div id="cms_home_wrapper">
			<h2>Débaldérin CMS</h2>
			<p>Welkom op de Débaldérin CMS. Met deze CMS is het mogelijk om de gehele website te onderhouden. Voor de verschillende pagina's gelden wisselende toegangsrechten, het kan dus zijn dat je niet alle pagina's kan wijzigen. Hieronder volgt een korte uitleg van alle pagina's aanwezig in deze CMS.</p>
			<p><a href="cms.page_structure.php">Web structuur</a> Met deze pagina is het mogelijk om het menu van de website (en van de <a href="cms.vcup_structure.php">Veluwecup website</a>) aan te passen, subpagina's toe te voegen of verwijderen en de inhoud van de subpagina's te wijzigen.</p>
			<p><a href="cms.news.php">Nieuws</a> Hier kunnen nieuwe nieuwsitems worden geschreven en toegevoegd worden aan zowel de hoofd website als de Veluwecup website.</p>
			<p><a href="cms.competition.php">Competitie</a> Dé pagina speciaal ontwikkeld voor de wedstrijdsecretaris! Hier kan hij/zij nieuwe teams, competities en wedstrijden aanmaken en natuurlijk de standen van reeds gespeelde wedstrijden invoeren.</p>
			<p><a href="cms.agenda.php">Agenda</a> Via deze pagina kunnen alle activiteiten van onze vereniging worden ingevoerd en in de agenda geplaatst worden. </p>
			<p><a href="cms.activities.php">Activiteiten</a> Op deze pagina kunnen nieuwe activiteiten worden aangemaakt, waarvoor geregistreerde leden zich kunnen aanmelden. </p>
			<p><a href="cms.photos.php">Foto's</a> Iedere geslaagde activiteit is natuurlijk niks zonder foto's! Op deze pagina kunnen nieuwe fotoalbums worden gemaakt en foto's kunnen worden toegevoegd aan reeds bestaande albums.</p>
			<p><a href="cms.admin.php">Admin</a> Hier kan de Admin nieuwe contactpersonen toevoegen aan het contactformulier, de rechten van geregistreerde gebruikers wijzigen en downloads toevoegen of verwijderen.</p>
			<p>Indien deze uitleg niet volstaat, of in het geval van andere vragen of opmerkingen, neem dan contact op met een van de leden van de WWW-cie (Okke, Taric of Peter) en zij zullen proberen je vragen zo snel mogelijk te beantwoorden.</p>
		</div>
		
	<!-- Footer -->
	<?php include('cms.footer.php'); ?>		
		
	</body>
</html>
<?php
	}
?>
