<?php require_once('../assets/config.php'); ?>

<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Nieuws</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<script type='text/javascript' src='../ckeditor/ckeditor.js'></script>
		<script type='text/javascript'>
			$(document).ready(function() {			
				CKEDITOR.timestamp='ABCD'; // Reload all .js and .css files
				CKEDITOR.config.width = '1098px';
				CKEDITOR.config.height = '500px';
				CKEDITOR.config.allowedContent = true;
				CKEDITOR.config.extraPlugins = 'youtube';
				CKEDITOR.config.youtube_width = '600';
				CKEDITOR.config.youtube_height = '450';
				CKEDITOR.config.youtube_related = false;
				CKEDITOR.config.forcePasteAsPlainText = true;
				CKEDITOR.replace( 'news_editor',
                {
					filebrowserBrowseUrl  :'<?=$config["absolute_path"]?>/ckeditor/plugins/image_browser.php',
					filebrowserUploadUrl  :'<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=File',
					filebrowserImageUploadUrl :'<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=Image',
				    filebrowserFlashUploadUrl : '<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=Flash'
				});							
				/* Toggle create news item form */
				$('#createNews').hide();
				
				$('#btn_createNewsItem').click(function() {
					$('#editNews').hide()
					$('#createNews').toggle()
					if ($(this).html() == 'Verberg') { $(this).html('Voeg nieuws toe'); }
					else { $(this).html('Verberg'); }
				});
				
				// Check if field is empty
				jQuery.fn.extend({
					validate_field: function() {
						var text = $(this).val();
						if (text.length == 0) {
							$(this).removeClass('correct');
							$(this).addClass('error');
							return false;
						} else {
							$(this).removeClass('error');
							$(this).addClass('correct');
							return true;
						}
					}
				})	
				
				// ON FOCUSOUT
				$(document).on('focusout', '#newsTopic, #news_content', function() {
					$(this).validate_field();
				});
				
				$(document).on('focusout', '#updateNewsTopic, #update_news_content', function() {
					$(this).validate_field();
				});				
				
				// ON SUBMIT
				$(document).on('click', '#newsSubmit', function(e) {
					if (!$('#newsTopic, #news_content').validate_field()) {
						e.preventDefault();
					}
				});
				
				$(document).on('click', '#newsUpdate', function(e) {
					if (!$('#updateNewsTopic, #update_news_content').validate_field()) {
						e.preventDefault();
					}
				});				
			});
		</script>
	<head>
	
	<body>
	
		<!-- Menu -->
		<?php include('cms.menu.html'); ?>

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
    	echo 'You don\'t have the rights to view and edit this page.';
    	echo '<a href="cms.logout.php">Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 
	 
		// We can display the user's username to them by reading it from the session array.  Remember that because 
		// a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 		
	
		<!-- Wrapper -->
		<section id="newsWrapper">
	
		<!-- Button new news item -->
		<button id="btn_createNewsItem">Voeg nieuws toe</button>
		
		<!-- Create news item -->
		<form action="" method="POST" id="createNews">
		
			<!-- Force display of form -->
			<script> #('#createNews').show(); </script>
			
			<!--<h2 class="h2header">NEW NEWS ITEM</h2>-->
			
			<label for="newsTopic" class="lblNewsTopic">Onderwerp: </label>
			<input type="text" name="newsTopic" id="newsTopic" placeholder="" />
		
			<label for="newsPublised" class="lblNewsPublished">Online: </label>
			<input type="radio" name="newsPublished" value="1" id="newsPublished" CHECKED />Yes
			<input type="radio" name="newsPublished" value="0" id="newsPublished" />No
						
			<textarea class="ckeditor" name="news_editor" id="news_content"></textarea>
			
			<label for="newsCategory" class="lblNewsCategory">Categorie: </label>
			<select name="newsCategory" id="newsCategory">
				<option value="news">Nieuws</option>
				<option value="match">Wedstrijdverslag</option>
				<option value="tournament">Toernooi</option>
				<option value="veluwecup">Veluwe Cup</option>
				<option value="gcup">G-cup</option>
			</select>
			
			<label for="newsIsSticky" class="lblNewsIsSticky">Sticky: </label>
			<input type="radio" name="newsIsSticky" value="1" id="newsIsSticky" />Yes
			<input type="radio" name="newsIsSticky" value="0" id="newsIsSticky" CHECKED />No

			<input type="submit" name="newsSubmit" id="newsSubmit" value="Voeg toe" />			
			<input type="reset" value="Reset" id="newsReset" />
			
			<?php
				if (isset($_POST['newsSubmit'])) {
					$topic = mysql_real_escape_string($_POST['newsTopic']);
					$date = date('Y-m-d');
					if (get_magic_quotes_gpc()) { $content = htmlspecialchars(stripslashes($_POST['news_editor'])); }
					else { $content = htmlspecialchars($_POST['news_editor']); }
					$category = mysql_real_escape_string($_POST['newsCategory']);
					$published = mysql_real_escape_string($_POST['newsPublished']);
					$sticky = mysql_real_escape_string($_POST['newsIsSticky']);

					$query = "INSERT INTO articles (title, pub_date, content, published, category, sticky) VALUES ('$topic', '$date', '$content', '$published', '$category', '$sticky')";
					mysql_query($query) or die(mysql_error());
					echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.news.php">';
				}
			?>
		
		</form>

		<!-- Edit news item -->		
		<form action="" method="POST" id="editNews">
		
			<!-- Force display of form -->
			<script> #('#editNews').show(); </script>

			<?php
				if (isset($_GET['editID'])) {
					$id = mysql_real_escape_string($_GET['editID']);
					$query = mysql_query("SELECT * FROM articles WHERE id = '$id' LIMIT 1");
					
					while ($article = mysql_fetch_array($query)) {
						echo '	<input type="hidden" name="articleID" value="' . $article['id'] . '" />
						
								<label for="updateNewsTopic" class="lblNewsTopic">Onderwerp: </label>
								<input type="text" name="updateNewsTopic" id="updateNewsTopic" placeholder="" value="' . $article['title'] .'"/>
		
								<label for="updateNewsPublised" class="lblNewsPublished">Online: </label>';
						if ($article['published']) {
						 	echo '	<input type="radio" name="updateNewsPublished" value="1" id="updateNewsPublished" CHECKED />Yes
									<input type="radio" name="updateNewsPublished" value="0" id="updateNewsPublished" />No';
						} else {
							echo '	<input type="radio" name="updateNewsPublished" value="1" id="updateNewsPublished" />Yes
									<input type="radio" name="updateNewsPublished" value="0" id="updateNewsPublished" CHECKED />No';
						}
							echo '
								<textarea class="ckeditor" name="update_news_editor" id="news_content">' . html_entity_decode($article['content']) . '</textarea>
			
								<label for="updateNewsCategory" class="lblNewsCategory">Categorie: </label>
								<select name="updateNewsCategory" id="updateNewsCategory">
									<option value="news">Nieuws</option>
									<option value="match">Wedstrijdverslag</option>
									<option value="tournament">Toernooi</option>
									<option value="veluwecup">Veluwe Cup</option>
									<option value="gcup">G-cup</option>
								</select>
			
								<label for="updateNewsIsSticky" class="lblNewsIsSticky">Sticky: </label>';
						if ($article['sticky']) {
							echo '	<input type="radio" name="updateNewsIsSticky" value="1" id="updateNewsIsSticky" CHECKED />Yes
									<input type="radio" name="updateNewsIsSticky" value="0" id="updateNewsIsSticky" />No';
						} else {
							echo '	<input type="radio" name="updateNewsIsSticky" value="1" id="updateNewsIsSticky" />Yes
									<input type="radio" name="updateNewsIsSticky" value="0" id="updateNewsIsSticky" CHECKED />No';
						}
							echo '
								<input type="submit" name="newsUpdate" id="newsUpdate" value="Wijzig" />
								<input type="reset" value="Reset" id="newsReset" />';
					}
				}
			?>
			
			<script>
				CKEDITOR.replace( 'update_news_editor',
                {
					filebrowserBrowseUrl  :'<?=$config["absolute_path"]?>/ckeditor/plugins/image_browser.php',
					filebrowserUploadUrl  :'<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=File',
					filebrowserImageUploadUrl :'<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=Image',
				    filebrowserFlashUploadUrl : '<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=Flash'
				});					
			</script>
			
			<?php	
				if (isset($_POST['newsUpdate'])) {
					$id = mysql_real_escape_string($_POST['articleID']);
					$topic = mysql_real_escape_string($_POST['updateNewsTopic']);
					$date = date('Y-m-d');
					if (get_magic_quotes_gpc()) { $content = htmlspecialchars(stripslashes($_POST['update_news_editor'])); }
					else { $content = htmlspecialchars($_POST['update_news_editor']); }
					$category = mysql_real_escape_string($_POST['updateNewsCategory']);
					$published = mysql_real_escape_string($_POST['updateNewsPublished']);
					$sticky = mysql_real_escape_string($_POST['updateNewsIsSticky']);

					$query = "UPDATE articles SET title='$topic', content='$content', category='$category', published='$published', sticky='$sticky' WHERE id='$id'";
					mysql_query($query) or die(mysql_error());
					echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.news.php">';
				}
				
			?>
		
		</form>		
		
		<!-- News table overview -->
		<table id="articles">
			<tr>
				<th id="newsTitle">Onderwerp</th>
				<th id="newsDate">Datum</th>
				<th id="newsCategory">Categorie</th>
				<th>Online</th>
				<th>Sticky</th>
				<th></th>
				<th></th>
			</tr>
		<?php
			
			$news = mysql_query("SELECT * FROM articles ORDER BY id DESC");
			
			while ($article = mysql_fetch_array($news)) {
				$date = strtotime($article['pub_date']);
				$date = date('d-m-Y', $date);
	echo '	<tr>
				<td class="articleTitle">' . $article['title'] . '</td>
				<td class="articlePubdate">' . $date . '</td>
				<td class="articleCategory">' . $article['category'] . '</td>
				<td class="articlePublished">'; 
				if ($article['published'] == 1) { echo 'yes'; } else { echo 'no'; }
	echo 		'</td>
				<td class="articleSticky">';
				if ($article['sticky'] == 1) { echo 'yes'; } else { echo 'no'; }
	echo 		'</td>
				<td class="articleEdit">
					<a href="cms.news.php?editID=' . $article['id'] . '" class="btn_edit"></a>
				</td>
				<td class="articleDelete">
					<form action="" method="POST">
						<button type="submit" name="deleteArticle" class="btn_remove" value="' . $article['id'] .'" onclick="return confirm(\'Are your sure you want to delete this?\')"></button>
					</form>
				</td>
			</tr>';
			
			}
			
			if (isset($_POST['deleteArticle'])) {
				$id = mysql_real_escape_string($_POST['deleteArticle']);
				$query = "DELETE FROM articles WHERE id = '$id'";
				mysql_query($query) or die();
				echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.news.php">';				
			}
			
		?>
		</table>

		<!-- Footer -->
		<?php include('cms.footer.php'); ?>
		
		</section>
		
	</body>
</html>

<?php 
	}
?>