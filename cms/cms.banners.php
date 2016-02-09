<!DOCTYPE html>

<?php require_once('../assets/config.php'); ?>

<html>

	<head>
		<meta charset='UTF-8'>
		<title>CMS - Banners</title>
		<link rel='stylesheet' type='text/css' href='../assets/css/cms_responsive.css'>
		<link rel='icon' href='img/logo_small.png'>
		<!--[if IE]><link rel="shortcut icon" href="img/logo_small.ico"><![endif]-->
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script type='text/javascript' src='js/general.js'></script>
        <script type='text/javascript' src='js/form_validation.js'></script>
        <script type='text/javascript' src='js/banners.js'></script>
        
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
                    <h2>Banners Help</h2>
                </header>
                <p>Wijzig de homepage banners en bijbehorende informatie. De website banners hebben een formaat van 1000px (breedte) bij 360px (hoogte).</p>
            </article>
            
            <?php
            	// View/edit homepage banner
            	
            	// Get available banners
            	$ext_allowed = array('jpg', 'jpeg', 'gif', 'png');
            	$forbidden = array('.', '..', '.DS_Store');
            	$path = '../assets/img/photo_banner/';
            	
            	$banner_query = mysql_query('SELECT * FROM banners WHERE banner_type = "home_banner" ORDER BY id LIMIT 3');
            	while ($b = mysql_fetch_assoc($banner_query)) {
            		echo '	<form id="' . $b['id'] . '" class="banner_form edit" method="POST">

            					<img src="' . $config['absolute_path'] . 'assets/img/photo_banner/' . $b['img'] . '" class="banner_preview">
            					            					
            					<label for="banner_title">Titel:</label>
            					<input type="text" name="banner_title" class="banner_title" value="' . $b['title'] . '">
            					
            					<br>
            					
            					<label for="banner_desc">Beschrijving:</label>
            					<textarea name="banner_desc" class="banner_desc">'. $b['description'] . '</textarea>
            					
            					<br>
            					
            					<label for="banner_url">Link:</label>
            					<input type="text" name="banner_url" class="banner_url" value="' . $b['url'] . '">
            					
            					<br>
            					
            					<label for="banner_url_text">Link tekst:</label>
            					<input type="text" name="banner_url_text" class="banner_url_text" value="' . $b['url_text'] . '">
            					
            					<br>
            					
            					<label for="banner_img">Afbeelding:</label>
            					<select name="banner_img" class="banner_img">';
								if ($handle = opendir($path)) {
									while (false !== ($entry = readdir($handle))) {
										$extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
										if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
											echo '<option value="' . $entry . '"' . ($b['img'] == $entry ? 'SELECTED' : null) . '>' . $entry . '</option>';
										}
									}
								}
            		echo '		</select>
            		
            					<br>
            					
            					<input type="submit" id="' . $b['id'] . '" class="submit_banner" value="Wijzig"> 
            				
            				</form>';
            	}
            ?>
            
            <img class='img_get_help' src='../assets/img/whats_this.PNG' alt='Help'>         
            <article class='help'>
                <header>
                    <h2>Veluwecup Banner</h2>
                </header>
                <p>Wijzig de Veluwecup banner. Selecteer een van de reeds aanwezige banners, of upload een nieuwe afbeelding onder aan de pagina.</p>
            </article>
            
        <?php    
            $id = $mysqli->query('SELECT id FROM banners WHERE banner_type = "vcup_banner" LIMIT 1')->fetch_object()->id;
            $img = $mysqli->query('SELECT img FROM banners WHERE banner_type = "vcup_banner" LIMIT 1')->fetch_object()->img;
		?>
			
			<form id="form_vcup_banner" class="edit">
				<img src="<?php echo $config['absolute_path'] . 'assets/img/photo_banner/' . $img; ?>" class="banner_preview">

				<label for="banner_img">Afbeelding:</label>
				<select name="banner_img" class="banner_img">
				<?php
					if ($handle = opendir($path)) {
						while (false !== ($entry = readdir($handle))) {
							$extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
							if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
								echo '<option value="' . $entry . '"' . ($img == $entry ? 'SELECTED' : null) . '>' . $entry . '</option>';
							}
						}
					}				
				?>
				</select>
				
				<br>
				
				<input type='submit' id="<?php echo $id; ?>" class="submit_banner" value="Wijzig">
				
			</form>

            <img class='img_get_help' src='../assets/img/whats_this.PNG' alt='Help'>         
            <article class='help'>
                <header>
                    <h2>Banner overzicht</h2>
                </header>
                <p>Hier zie je alle beschikbare banners op de server (rechts). Upload nieuwe banners via het formulier links en verwijder banner door op deze te klikken.</p>
            </article>

            <section class='clear'>
		        <section id='upload_files'>
		        	<h2>Upload Banners</h2>

				    <form id='upload' method='POST' action='handle_upload.php?name=banner' enctype='multipart/form-data' class='edit'>
				    	
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
			
				<section id="banner_overview">
					<h2>Beschikbare Banners</h2>
					<?php
                        if ($handle = opendir($path)) {
                            while (false !== ($entry = readdir($handle))) {
                                $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                                if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
                                    echo '  <div class="banner_small">
                                                <h3>' . $entry . '</h3>
                                                <img src="' . $path . $entry . '" id="' . $entry . '">
                                            </div>';
                                }
                            }
                        } 
					?>
				</section>
			</section>

        </main>
        
        <?php 
        	}
        	include('cms.footer.php'); 
        ?>
        
    </body>
    
</html>
