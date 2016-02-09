<!DOCTYPE html>

<?php require_once('../assets/config.php'); ?>

<html>

	<head>
		<meta charset='UTF-8'>
		<title>CMS - Downloads</title>
		<link rel='stylesheet' type='text/css' href='../assets/css/cms_responsive.css'>
		<link rel='icon' href='img/logo_small.png'>
		<!--[if IE]><link rel="shortcut icon" href="img/logo_small.ico"><![endif]-->
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script type='text/javascript' src='js/general.js'></script>
		<script type='text/javascript' src='js/downloads.js'></script>
		<!-- JavaScript Includes  File Upload-->
		<script src="../assets/js/jquery.knob.js"></script>
		<script src="../assets/js/jquery.ui.widget.js"></script>
		<script src="../assets/js/jquery.iframe-transport.js"></script>
		<script src="../assets/js/jquery.fileupload.js"></script>
		<script src="js/upload_progress.js"></script>		
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
        
            <img class='img_get_help' src='../assets/img/whats_this.PNG' alt='Help'>          
            <article class='help'>
                <header>
                    <h2>Downloads uitleg</h2>
                </header>
                <p>Upload bestanden door in de "drop-area" te droppen, of via de "Browse"-knop. Bestanden met de extensies jpg, jpeg, png, gif, pdf, doc, docx, xls, xlsx en zip kunnen worden geüpload.</p>
                <p>Alle bestanden weergegeven in het onderstaande overzicht zijn beschikbaar als download in Debbie's Hangout.</p>
            </article>
            
            <section class='clear'>
		        <section id='upload_files'>
		        	<h2>Upload bestanden</h2>

				    <form id='upload' method='POST' action='handle_upload.php?name=download' enctype='multipart/form-data' class='edit'>
				    	
				    	<div id='drop'>
				    		<h3>Drop Here</h3>
				    		<a>Browse</a>
				    		<input type='file' name='upl' multiple />
				    	</div>       	
						
				    </form>
								    	
					<ul id='upload_list'>
						<div id="nr_uploads" data-count="0"></div>
						<!-- The file uploads will be shown here -->
					</ul>

				</section>
			
				<section id="downloads">
					<h2>Downloads</h2>
					<ul id="list_downloads">
						<!-- All files in the downloads folder will be listed here -->
					</ul>
				</section>
			</section>
            
        </main>
        
        <?php 
        	}
        	include('cms.footer.php'); 
        ?>
        
    </body>
    
</html>
