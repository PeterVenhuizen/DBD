<?php require_once('../assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Downloads</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type='text/javascript' src='../assets/js/validation_functions.js'></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<script type='text/javascript'>	
			$(document).ready(function() {
				
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
				
		<style type='text/css'>
			#downloadwrapper {
				width: 1000px;
				margin: 0 auto;
				overflow: hidden;
			}
			#upload_files {
				width: 300px;
				padding: 5px;
				float: left;
				min-height: 400px;
			}
			#upload_files h1 {
				font-family: 'Lato';
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
				font-family: 'Lato';
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
				background: url('../img/icons.png') no-repeat;
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
				background-image: url('../img/png.png');
				padding-left: 30px;
				background-repeat: no-repeat;
			}				
			.jpg, .jpeg {
				background-image: url('../img/jpg.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.doc, .docx {
				background-image: url('../img/doc.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.xls, xlsx {
				background-image: url('../img/xls.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.pdf {
				background-image: url('../img/pdf.png');
				padding-left: 30px;
				background-repeat: no-repeat;				
			}
			.delete_download {
				float: right;
				height: 20px;
				width: 20px;
				padding-left: -30px;
				background-image: url('../img/dialog-close.png');
				background-repeat: no-repeat;
				background-position: center center;
				cursor: pointer;
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
    
	if ($_SESSION['user']['rights'] < 3) {
    	echo 'You don\'t have the rights to view and edit this page.';
    	echo '<a href="cms.logout.php">Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 

?> 

			<section id="downloadwrapper">

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
					<script src="../js/jquery.knob.js"></script>

					<!-- jQuery File Upload Dependencies -->
					<script src="../js/jquery.ui.widget.js"></script>
					<script src="../js/jquery.iframe-transport.js"></script>
					<script src="../js/jquery.fileupload.js"></script>
		
					<!-- Our main JS file -->
					<script src="../js/script.js"></script>
					
					<div class="upload_info">Upload de bestanden door ze naar de "drop-area" te slepen of door of op de knop "Browse" te klikken.<br />De volgende bestanden (.jpg, .png, .gif, .pdf, .doc(x) en .xls(x)) kunnen worden geüpload.</div>
					
				</section>
				
				<section id="downloads">
					<h1>Downloads</h1>
					<ul id="list_downloads">
						<!-- All files in the downloads folder will be listed here -->
					</ul>
					<div class="download_info">Upload de bestanden door ze naar de "drop-area" te slepen of door of op de knop "Browse" te klikken.<br /><br />Alle bestanden in de download map (hier weergegeven) zijn beschikbaar als download op de leden pagina. Verwijder bestanden door op het rode kruisje te drukken.</div>					
				</section>

		</section>

		<!-- Footer -->
		<?php include('cms.footer.php'); ?>

		</section>
	</body>
</html>
<?php
	}
?>