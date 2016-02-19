<?php require_once('../assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Foto's</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type='text/javascript' src='../assets/js/validation_functions.js'></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<script type='text/javascript'>	
			$(document).ready(function() {
				//Disable all fields
				$('.upload_step *').prop('disabled', true);
				$('.upload_step').addClass('inactive');
				$('.upload_step:first-child *').prop('disabled', false);
				$('.upload_step:first-child').removeClass('inactive');
				
				//Overlay all inactive fields 
				$('.inactive').addClass('step_overlay');			
				
				//Change visibility
				$('#btn_step_one').click(function() {
					var upload_type = $('#upload_choice:checked').val();
					if (upload_type == 'album_new') {
						$('.upload_step:nth-child(2) *').prop('disabled', false);
						$('.upload_step:nth-child(2)').removeClass('inactive step_overlay');
					} else if (upload_type == 'album_existing') {
						$('.upload_step:nth-child(3) *').prop('disabled', false);
						$('.upload_step:nth-child(3)').removeClass('inactive step_overlay');						
					} else if (upload_type == 'news_images') {
						window.location = 'cms.photos.php?type=news&name=newsImages';
					}
				});
				
				//Do PHP and set POST variables
				$('#btn_step_two').click(function() {					
					// Set POST data
					var album_name = $('#album_name').val();
					album_name = album_name.replace(/\s+/g, '_');
					var visibility = $('#album_visibility:checked').val();
					var album_date = $('#album_date').val();
					
					if ($('#album_name').is_not_empty() && $('#album_date').is_valid_date() && $('#album_name').val() !== 'newsImages') {

						// Create new photo album folder
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.createPhotoAlbum.php',
							data: 'album_name='+album_name+'&visibility='+visibility+'&album_date='+album_date,
							success: function() {
								// Refer back to page with post data
								window.location = 'cms.photos.php?type=photo&name='+album_name;
							}
						})	
					}
									
				})
				$('#btn_step_two_half').click(function() {
					// Set POST data and refer back to page
					var album_name = $('.select_folder:selected').val();
					album_name = album_name.replace(/\s+/g, '_');					
					window.location = 'cms.photos.php?type=add&name='+album_name;
				})		
				
				//Create new album validation
				$('#album_name').focusout(function() {
					$(this).is_not_empty();
				});
				$('#album_date').focusout(function() {
					$(this).is_valid_date();
				});		
				
				
				//Browse and manage existing album
				$('#browse_album').click(function() {
					var album_name = $('.select_folder:selected').val();
					$.ajax({
						type: 'POST',
						url: 'actions/ajax.managePhotos.php',
						data: 'album_name='+album_name,
						success: function(result) {
							$('#manage_album').html(result);
						}
					});
				});

				//Delete photo album				
				$(document).on('click', '#btn_delete_album', function() {
					var album_name = $(this).val();
					if (confirm('Weet je zeker dat je fotoalbum "'+album_name+'" wilt verwijderen?')) {
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.managePhotos.php',
							data: 'delete_album='+album_name,
							success: function(result) {
								location.reload();
							}
						});		
					}			
				});
				
				//Delete photo
				$(document).on('click', '.img_delete', function() {
					var photo = $(this).attr('id');
					var album = $('#browse_album_name').html();
					var album_name = album.replace(/ /g, '_');
					if (confirm('Weet je zeker dat je deze foto wilt verwijderen?')) {
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.managePhotos.php',
							data: 'delete_photo='+photo+'&album='+album_name,
							success: function(result) {
								$.ajax({
									type: 'POST',
									url: 'actions/ajax.managePhotos.php',
									data: 'album_name='+album_name,
									success: function(result) {
										$('#manage_album').html(result);
									}
								});
							}
						});		
					}						
				});
				
			});
		</script>
		<style type='text/css'>			
			/* MANAGE PHOTOS */
			#browse_album {
				display: inline-block;
				border: 1px solid #cecece;
				width: 100px; 
				height: 30px; 
				cursor: pointer;	
				text-align: center;
				line-height: 30px;	
				text-decoration: none;	
				color: rgb(87, 129, 247);					
			}
			#manage_album {
				width: 1170px;
				margin: 0 auto;
				padding: 5px;
				border: 1px solid #E1E1E1;
				overflow: hidden;
				position: relative;
			}			
			.portrait {
				height: 200px;
				float: left;
				margin: 5px;
			}
			.landscape {
				height: 200px;
				float: left;
				margin: 5px;
			}
			#manage_album img:hover {
				opacity: 0.5;
				-moz-opacity: 0.5;
				filter:alpha(opacity=50);
			}
			#browse_album_name {
				font-family: 'Lato', sans-serif;
				color: rgb(87, 129, 247);
				font-weight: bold;				
				margin: 10px;
				padding: 0;
			}		
			#manage_album_description {
				margin: 10px;
				padding: 0;
			}
			#btn_delete_album {
				border: 1px solid #cecece;
				width: 100px; 
				height: 30px; 
				margin: 10px 0 10px 10px;
				cursor: pointer;	
				position: absolute; 
				top: 5px;
				right: 15px;
			}			
		</style>
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
    	echo 'Je beschikt niet over de vereiste rechten om deze pagina te bewerken! <a href="cms.logout.php"> Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 

?> 

			<section id="uploadwrapper">

				<!-- SELECT UPLOAD CHOICE -->
				<section class="upload_step">
	
					<h1><b>1</b>: Selecteer type upload</h1>
	
					<form id="define_upload_choice">
	
						<label class="upload_choice"><input type="radio" name="upload_choice" id="upload_choice" value="album_new" CHECKED />Fotoalbum (maak een nieuw album)</label>
						<label class="upload_choice"><input type="radio" name="upload_choice" id="upload_choice" value="album_existing" />Fotoalbum (voeg toe aan bestaand album)</label>
						<label class="upload_choice"><input type="radio" name="upload_choice" id="upload_choice" value="news_images" />Foto voor nieuwsitem of subpagina</label>
						
					</form>
	
					<div class="upload_info">Maak een keuze of je een nieuw fotoalbum wilt maken of dat je foto's wilt toevoegen aan een bestaand album en klik op volgende-knop.</div>
	
					<button id="btn_step_one" class="next">Volgende</button>
	
				</section>

				<!-- CREATE NEW PHOTO ALBUM -->
				<section class="upload_step">
					
					<h1><b>2</b>: Maak nieuw fotoalbum</h1>
				
					<form id="create_new_photo_album" method="POST">

						<label for="album_name">Album naam:</label>
						<br />
						<input type="text" name="album_name" id="album_name" />
	
						<br />
	
						<label><input type="radio" name="album_visibility" id="album_visibility" value="0" CHECKED>Openbaar</label>
						<br />
						<label><input type="radio" name="album_visibility" id="album_visibility" value="1">Gesloten (alleen voor leden)</label>

						<br />
						
						<label for="album_date">Datum</label>
						<input type="text" name="album_date" id="album_date" placeholder="bv. 31-01-2014" />
	
					</form>
	
					<div class="upload_info">Geef hier het nieuwe foto album een naam (bv. veluwecup 2013 - prive), bepaal of het album voor iedereen of alleen voor leden zichtbaar is en geef de datum van het album in.</div>
	
					<button id="btn_step_two" class="next">Volgende</button>
	
				</section>

				<!-- SELECT EXISTING PHOTO ALBUM -->
				<section class="upload_step">
				
					<h1><b>2</b>: Selecteer fotoalbum</h1>
				
					<form id="select_existing_photo_album" method="POST">
	
						<label for="select_folder">Selecteer bestaand album:</label>
						<br />
						<select name="select_folder" id="select_folder">
	
					<?php
						// Select existing photo albums						
						$albums = mysql_query("SELECT * FROM photo_albums ORDER BY name");
						while ($album = mysql_fetch_array($albums)) {
							echo '	<option name="select_folder" class="select_folder" value="' . $album['name'] . '">' . str_replace('_', ' ', $album['name']) . ' - ' . date('d-m-Y', strtotime($album['date'])) . '</option>';
						}
						/*						
						$skip_folders = array(".", "..", ".DS_Store", "newsImages");
						$path = $_SERVER[DOCUMENT_ROOT] . '/images/';
						if ($handle = opendir($path)) {
							while (false !== ($entry = readdir($handle))) {
								if (!in_array($entry, $skip_folders)) {
									echo '<option name="select_folder" class="select_folder" value="' . $entry . '">' . str_replace('_', ' ', $entry) . '</option>';
								}
							}
						}
						*/
					?>	
	
						</select>
	
					</form>
	
					<div class="upload_info">Selecteer een bestaand fotoalbum om hieraan nieuwe foto's toe te voegen en klik dan op volgende.</div>
					
					<a href="#a_manage_album" id="browse_album">Bekijk album</a>
					<button id="btn_step_two_half" class="next">Volgende</button>
	
				</section>

				<!-- UPLOAD -->
				<section class="upload_step">
					
					<h1><b>3</b>: Upload bestanden</h1>
					
					<?php $name = $_GET['name']; ?>
					
					<form id="upload" method="post" action="<?php echo 'handle_upload.php?name=' . $name; ?>" enctype="multipart/form-data">
						<div id="drop">
							Drop Here

							<a>Browse</a>
							<input type="file" name="upl" multiple />
						</div>

						<span>Uploaden in "<?php echo str_replace('_', ' ', $name); ?>"</span>

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
					
					<div class="upload_info">Upload de bestanden door ze naar de "drop-area" te slepen of door of op de knop "Browse" te klikken.<br />De volgende bestanden (.jpg, .png, .gif) kunnen worden geüpload. Foto's bestemd voor fotoalbums worden automatisch verkleind.</div>
					
				</section>

		</section>
		
		<section id="manage_album">

		</section>

<?php
	if (isset($_GET['type'])) {
		if ($_GET['type'] == 'photo') {
			?> <script>
				$(document).ready(function() {
					$('.upload_step:nth-child(2) *').prop('disabled', false);
					$('.upload_step:nth-child(2)').removeClass('inactive step_overlay');		
					$('.upload_step:last-child() *').prop('disabled', false);
					$('.upload_step:last-child()').removeClass('inactive step_overlay');				
				});
			</script> <?php			
		} else if ($_GET['type'] == 'add') {
			?> <script>
				$(document).ready(function() {
					$('.upload_step:nth-child(3) *').prop('disabled', false);
					$('.upload_step:nth-child(3)').removeClass('inactive step_overlay');		
					$('.upload_step:last-child() *').prop('disabled', false);
					$('.upload_step:last-child()').removeClass('inactive step_overlay');				
				});
			</script> <?php
		} else {
			?> <script>
				$(document).ready(function() {		
					$('.upload_step:last-child() *').prop('disabled', false);
					$('.upload_step:last-child()').removeClass('inactive step_overlay');				
				});
			</script> <?php		
		}
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