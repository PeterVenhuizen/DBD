<!DOCTYPE html>

<?php require_once('../assets/config.php'); ?>

<html>

	<head>
		<meta charset='UTF-8'>
		<title>CMS - W.S.K.V. Débaldérin</title>
		<link rel='stylesheet' type='text/css' href='../assets/css/cms_responsive.css'>
		<link rel='icon' href='img/logo_small.png'>
		<!--[if IE]><link rel="shortcut icon" href="img/logo_small.ico"><![endif]-->
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script type="text/javascript" src='js/general.js'></script>
		<meta name="viewport" content="initial-scale=1">		
	</head>
    
    <body>
        
        <?php 
        	include('cms.menu.php'); 
        	
        	if (empty($_SESSION['user'])) {
        		header("Location: ../cms/cms.login.php");
        		die();
        	}
        	
        	if ($_SESSION['user']['rights'] < 1) {
        		echo 'Je beschikt niet over de vereiste gebruikersrechten om deze pagina te zien! Voor vragen neem contact op met de <a href="www.debalderin.wur.nl/Contact/">Admin</a>';
        	} else {
        ?>
        
        <main>
        
            <article>
                <header>
                    <h2>CMS - W.S.K.V. Débaldérin</h2>
                </header>
                <p>Welkom op de het Content Management System (CMS) van Débaldérin, met deze CMS is het mogelijk om de gehele website te onderhouden. De toegang tot de verschillende CMS pagina's is afhankelijke van de toegekende gebruikersrechten. Hieronder een korte beschrijving van iedere pagina.</p>
                <ul>
                    <li><a href='cms.activities.php'>Activiteiten</a> - Voeg activiteiten toe aan Debbie's Hangout. Geregistreerde leden kunnen zich voor deze activiteiten aanmelden.</li>
                    <li><a href='cms.agenda.php'>Agenda</a> - Voeg activiteiten (trainingen, toernooien, etc.) toe. Deze verschijnen in de agenda op de home pagina.</li>
                    <li><a href='cms.banners.php'>Banners</a> - Manage de fotobanner op de homepage en het Veluwecup subdomein.</li>
                    <li><a href='cms.competition.php'>Competitie</a> - Dé pagina voor de wedstrijdsecretaris. Manage teams, competities, wedstrijden en standen.</li>
                    <li><a href='cms.contact.php'>Contact</a> - Manage contactpagina, bv. contactpersonen, informatie van de wedstrijdsecretaris, etc.</li>
                    <li><a href='cms.downloads.php'>Downloads</a> - Voeg downloads (zoals fotoalbum zip archieven) toe aan Debbie's Hangout.</li>
                    <li><a href='cms.photos.php'>Foto's</a> - Manage fotoalbums en upload foto's voor nieuwsitems (ook mogelijk vanuit de nieuws-editor).</li>
                    <li><a href='cms.users.php'>Leden</a> - Overzicht van alle geregistreerde gebruikers. Op deze pagina kunnen de CMS toegangsrechten per gebruiker gewijzigd worden.</li>
                    <li><a href='cms.news.php'>Nieuws</a> - Maak en wijzig nieuwsitems.</li>
                    <li><a href='cms.vcup_structure.php'>Veluwecup</a> - Manage menu, subpagina's en inhoud van het Veluwecup subdomein.</li>
                    <li><a href='cms.page_structure.php'>Web structuur</a> - Manage menu, subpagina's en inhoude van de Débaldérin website.</li>
                </ul>
            </article>
            
        </main>
        
        <?php 
        	}
        	include('cms.footer.php'); 
        ?>
        
    </body>
    
</html>
