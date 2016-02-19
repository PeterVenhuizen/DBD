<?php require_once('../assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Admin</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type='text/javascript' src='../assets/js/validation_functions.js'></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<style type='text/css'>
			#internal_links {
				width: 1000px;
				border: 1px solid #e1e1e1;
				margin: 0 auto;
				margin-bottom: 20px;
			}
			#internal_links a {
				text-decoration: none;
				line-height: 30px;
				margin: 0 10px;
			}
			#downloadwrapper {
				width: 1000px;
				margin: 0 auto;
				overflow: hidden;
				margin-top: 30px;
			}
			#downloadwrapper h2 {
				font-size: 26px;
				font-weight: normal;
				margin: 10px 0 10px 0;			
			}
			#upload_files {
				width: 300px;
				padding: 5px;
				float: left;
				min-height: 400px;
				margin-left: -5px;
			}
			#upload_files h1 {
				font-size: 22px;
				font-weight: normal;
				margin: 0 0 5px 0;
			}	
			#downloads {
				width: 450px;
				padding: 5px;
				float: left;
				min-height: 400px;
				overflow: hidden;
				margin-left: 10px;	
			}
			#downloads h1 {
				font-size: 22px;
				font-weight: normal;
				margin: 0 0 5px 8px;		
			}
			.upload_info {
				width: 295px;
				margin: 5px 0;
				padding: 2px;
				border: 1px dashed #e1e1e1;
			}	
			.download_info {
				margin: 5px;
				padding: 2px;
				border: 1px dashed #e1e1e1;
			}
			#drop{
				padding: 60px 50px;
				margin-bottom: 10px;
				border: 3px dashed #e1e1e1;
				border-radius: 3px;
				text-align: center;
				text-transform: uppercase;
				font-size:16px;
				font-weight:bold;
			}
			#drop a{
				padding:12px 26px;
				color: #000;
				font-size:14px;
				border-radius:2px;
				cursor:pointer;
				display:inline-block;
				margin-top:12px;
				line-height:1;
				border: 2px solid #000;
			}
			#drop a:hover{
				background-color:#e1e1e1;
			}
			#drop input{
				display:none;
			}	
			
			#upload ul{
				list-style:none;
				margin-left: -40px;
			}

			#upload ul li{
				padding: 5px;
				height: 20px;
				position: relative;
			}

			#upload ul li input{
				display: none;
			}

			#upload ul li p{
				width: 144px;
				overflow: hidden;
				white-space: nowrap;
				color: #000;
				font-size: 16px;
				font-weight: bold;
				position: absolute;
				top: -9px;
				left: 60px;
			}

			#upload ul li canvas{
				top: 5px;
				left: 32px;
				height: 20px;
				position: absolute;
			}

			#upload ul li span{
				width: 15px;
				height: 12px;
				background: url('../assets/img/icons.png') no-repeat;
				position: absolute;
				top: 9px;
				right: 33px;
				cursor:pointer;
			}

			#upload ul li.working span{
				height: 16px;
				background-position: 0 -12px;
			}

			#upload ul li.error p{
				color:red;
			}	
			#nr_uploads {
				margin: 0 0 0 10px;
				padding: 0;
			}
			count {
				font-weight: bold;
			}
			
			ul#list_downloads {
				list-style-type: none;
			}
			li.download {
				height: 30px;
				width: 400px;
				line-height: 30px;
				margin-bottom: 2px;
				margin-left: -30px;
				overflow: hidden;
				display: inline-block;				
			}
			.png {
				background-image: url('../assets/img/png.png');
				padding-left: 30px;
				background-repeat: no-repeat;
			}				
			.jpg, .jpeg {
				background-image: url('../assets/img/jpg.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.doc, .docx {
				background-image: url('../assets/img/doc.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.xls, xlsx {
				background-image: url('../assets/img/xls.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.pdf {
				background-image: url('../assets/img/pdf.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.mp3 {
				background-image: url('../assets/img/mp3.png');
				padding-left: 30px;
				background-repeat: no-repeat;
			}
			.gif {
				background-image: url('../assets/img/mp3.png');
				padding-left: 30px;
				background-repeat: no-repeat;
			}
			.zip {
				background-image: url('../assets/img/zip.png');
				padding-left: 30px;
				background-repeat: no-repeat;
			}
			.unknown {
				background-image: url('../assets/img/unknown.png');
				padding-left: 30px;
				background-repeat: no-repeat;
			}
			.delete_download {
				float: right;
				height: 20px;
				width: 20px;
				padding-left: -30px;
				background-image: url('../assets/img/dialog-close.png');
				background-repeat: no-repeat;
				background-position: center center;
				cursor: pointer;
			}
		</style>		
		<script type='text/javascript'>
			$(document).ready(function() {

				$('#addContact').hide();
				$('.btn_new_contact').click(function() {
					$('#addContact').toggle();
					if ($(this).html() == 'Verberg') { $(this).html('Nieuw contact'); }
					else { $(this).html('Verberg'); }
				});
				
				// Validate if fields are empty
				$('#contactFunction').focusout(function() {
					$(this).is_not_empty();
				});
				
				// Validate if a valid date is inserted
				$('#contactEmail').focusout(function() {
					$(this).is_valid_email();
				});
				
				// ON SUBMIT: Check edit menu item
				$(document).on('click', '#submitContact', function(e) {
					if (!$('#contactFunction').is_not_empty() || !$('#contactEmail').is_valid_email()) {
						e.preventDefault();
					} 
				});
				
				
				$(document).on('click', '.btn_edit', function() {
					var id = $(this).parent().prev().find('option.user_rights').attr('class').split(' ')[1];
					var rights = $(this).parent().prev().find('option.user_rights:selected').val();
					$.ajax({
						type: 'POST',
						url: 'actions/ajax.miscellaneous.php',
						data: {user_id: id, user_rights: rights},
						success: function() {
							location.reload();
						}
					})				
				});		
				
				$(document).on('click', '#btn_delete_user', function() {
					var id = $(this).val();
					if (confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')) {
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.miscellaneous.php',
							data: {delete_id: id},
							success: function(result) {
								$('#users').html(result);
							}
						});	
					}			
				});
				
				/* DOWNLOADS */
				//Load downloads on page load
				$.post('actions/ajax.loadDownloads.php', function(result) {
					$('#list_downloads').html(result);
				});
				
				//Update downloads if upload is finished
				$('#nr_uploads').bind("DOMSubtreeModified", function() {
					setTimeout(function() {
						$.post('actions/ajax.loadDownloads.php', function(result) {
							$('#list_downloads').html(result);
						});	
					}, 100);			
				});
				
				//Delete file and refresh
				$(document).on('click', '.delete_download', function() {
					var filename = $(this).attr('id');
					if (confirm('Weet je zeker dat je "'+filename+'" wilt verwijderen?')) {
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.loadDownloads.php',
							data: 'delete_file='+filename,
							success: function(result) {
								$('#list_downloads').html(result);
							}
						});
					}					
				});				
						

			});
		</script>
		
	</head>
	
	<body>
		
		<!-- Menu -->
		<?php include('cms.menu.html'); ?>
			
		<!-- Main CMS content -->	
		<section id="cmswrapper">
		
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
    	echo 'Je beschikt niet over de vereiste rechten om deze pagina te bewerken! <a href="cms.logout.php">Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 

		if ($_SESSION['user']['rights'] == 3) {
		
?> 			

			<div id="internal_links">
				<a href="#a_edit_contacts">Wijzig contacten</a> |
				<a href="#a_user_rights">Wijzig gebruikers rechten</a> | 
				<a href="#a_downloads">Wijzig downloads</a>
			</div>

			<section id="contactWrapper">
				<a name="a_edit_contacts"></a>
				<h2>Wijzig contacten</h2>
				<table id="contacts">
					<tr>
						<th>Functie</th>
						<th>Email</th>
						<th></th>
					</tr>
					<?php
						$contacts = mysql_query("SELECT * FROM contacts");
						while ($contact = mysql_fetch_array($contacts)) {
							echo '	<tr>
										<td class="contactFunction">' . $contact['function'] . '</td>
										<td class="contactEmail">' .$contact['email'] . '</td>
										<td class="contactDelete">
											<form action="" method="POST">
												<button type="submit" name="deleteContact" class="btn_remove" value="' . $contact['id'] .'" onclick="return confirm(\'Weet je zeker dat je dit contact wilt verwijderen?\')"></button>
											</form>
										</td>
									</tr>';
						}
				
						// Delete agenda event
						if (isset($_POST['deleteContact'])) {
							$id = mysql_real_escape_string($_POST['deleteContact']);
							$remove_query = "DELETE FROM contacts WHERE id=$id";
							mysql_query($remove_query) or die();
					
					?>
							<!-- Reload page after event is deleted -->
							<script>
								location.reload();
							</script>
					<?php
					
						}
				
					?>
				</table>
		
				<button class="btn_new_contact">Nieuw contact</button>
	
				<form action="" method="POST" id="addContact">
			
					<h3>Voeg nieuw contact toe</h3>
			
					<label for="contactFunction">Functie:</label>
					<input type="text" name="contactFunction" id="contactFunction" />

					<br />
					
					<label for="contactEmail">Email:</label>
					<input type="email" name="contactEmail" id="contactEmail" />

					<br />
			
					<input type="submit" name="submitContact" id="submitContact" value="Voeg toe!" />
			
					<?php
						if (isset($_POST['submitContact'])) {
							$function = mysql_real_escape_string($_POST['contactFunction']);
							$email = mysql_real_escape_string($_POST['contactEmail']);
							
							$query = "INSERT INTO contacts (function, email) VALUES (:function, :email)";
							$query_params = array(':function' => $function, ':email' => $email); 
							
							try {
								$stmt = $db->prepare($query);
								$stmt->execute($query_params);
					?>
							<!-- Reload page after event is deleted -->
							<script>
								location.reload();
							</script>
					<?php								
							} catch(PDOException $ex) { 
								// Note: On a production website, you should not output $ex->getMessage(). 
								// It may provide an attacker with helpful information about your code.  
								die("Failed to run query: " . $ex->getMessage()); 
							} 
						}
					?>
			
				</form>
		
			</section>

			<section id="userRights">
				<a name="a_user_rights"></a>
				<h2>Wijzig gebruikers rechten</h2>
				
				<table id="users">
					<tr>
						<th>Naam</th>
						<th>Email</th>
						<th>Rechten</th>
						<th></th>
						<th></th>
					</tr>
					<?php
						$users = mysql_query("SELECT * FROM users ORDER BY first_name");
						while ($user = mysql_fetch_array($users)) {
							echo '	<tr>
										<td class="userName">' . $user['first_name'] . ' ' . $user['prefix'] . ' ' . $user['last_name'] . '</td>
										<td class="userEmail">' . $user['email'] . ' </td>
										<td class="userRights">
											<select name="user_rights">';
					?>						
												<option class="user_rights <?php echo $user['id']; ?>" value="0" <?php if ($user['rights'] == 0) { echo "SELECTED"; } ?>>0</option>
												<option class="user_rights <?php echo $user['id']; ?>" value="1" <?php if ($user['rights'] == 1) { echo "SELECTED"; } ?>>1</option>
												<option class="user_rights <?php echo $user['id']; ?>" value="2" <?php if ($user['rights'] == 2) { echo "SELECTED"; } ?>>2</option>
												<option class="user_rights <?php echo $user['id']; ?>" value="3" <?php if ($user['rights'] == 3) { echo "SELECTED"; } ?>>3</option>																																				
					<?php												
							echo '			</select>
											<!--<input type="text" name="user_rights" class="user_rights ' . $user['id'] . '" value="' . $user['rights'] . '" /></td>-->
										<td class="userEdit">
											<button type="submit" name="editUserRights" class="btn_edit" value="' . $user['id'] .'"></button>
										</td>
										<td class="userDelete">
											<button name="btn_delete_user" id="btn_delete_user" class="btn_remove" value="' . $user['id'] . '"></button>
										</td>
									</tr>';
						}
					?>
				</table>
				
				<aside id="rights_explanation">
					<h2>Wijzig gebruikers rechten</h2>
					<p>Hier kan je de rechten van de geregistreerde gebruikers wijzigen. Een standaard gebruiker heeft geen rechten en kan niet inloggen in de CMS.</p>
					<p>0 - Standaard gebruiker</p>
					<p>1 - Gebruiker foto's en downloads uploaden en nieuws en agenda items aanmaken, wijzigen en verwijderen. </p>
					<p>2 - Gebruiker kan competities en wedstrijden aanmaken, wijzigen en verwijderen</p>
					<p>3 - Alle rechten (Admin). Een admin kan de web structuur aanpassen (menu en submenu), nieuwe contacten toevoegen aan de contact pagina en de rechten van andere gebruikers wijzigen.</p>					
				</aside>	
					
			</section>

<?php
		}
		if ($_SESSION['user']['rights'] >= 1) {
?>

			<section id="downloadwrapper">
				<a name="a_downloads"></a>
				<h2>Wijzig downloads</h2>

				<!-- UPLOAD -->
				<section id="upload_files">
					
					<h1>Upload bestanden</h1>
					
					<form id="upload" method="post" action="handle_upload.php?name=download" enctype="multipart/form-data">
						<div id="drop">
							Drop Here

							<a>Browse</a>
							<input type="file" name="upl" multiple />
						</div>

						<ul>
							<div id="nr_uploads" data-count="0"></div>
							<!-- The file uploads will be shown here -->
						</ul>

					</form>

					<!-- JavaScript Includes -->
					<script src="../assets/js/jquery.knob.js"></script>

					<!-- jQuery File Upload Dependencies -->
					<script src="../assets/js/jquery.ui.widget.js"></script>
					<script src="../assets/js/jquery.iframe-transport.js"></script>
					<script src="../assets/js/jquery.fileupload.js"></script>
		
					<!-- Our main JS file -->
					<script src="../assets/js/script.js"></script>
					
					<div class="upload_info">Upload de bestanden door ze naar de "drop-area" te slepen of door of op de knop "Browse" te klikken.<br />De volgende bestanden (.jpg, .png, .gif, .pdf, .doc(x), .xls(x) en .zip) kunnen worden geüpload.</div>
					
				</section>
				
				<section id="downloads">
					<h1>Downloads</h1>
					<ul id="list_downloads">
						<!-- All files in the downloads folder will be listed here -->
					</ul>
					<div class="download_info">Upload de bestanden door ze naar de "drop-area" te slepen of door of op de knop "Browse" te klikken.<br /><br />Alle bestanden in de download map (hier weergegeven) zijn beschikbaar als download op de leden pagina. Verwijder bestanden door op het rode kruisje te drukken.</div>					
				</section>

		</section>

<?php
		}
?>
			
		<!-- Footer -->	
		<?php include('cms.footer.php'); ?>
		
		</section>
	</body>
</html>
<?php
	}
?>
