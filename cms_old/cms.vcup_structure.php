<?php require_once('../assets/config.php'); ?>

<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Veluwecup structuur</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<script src='../ckeditor/ckeditor.js'></script>
		<script src='js/vcup_structure.js'></script>
	</head>
	
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
    
	if ($_SESSION['user']['rights'] < 3) {
    	echo 'You don\'t have the rights to view and edit this page.';
    	echo '<a href="cms.logout.php">Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 
	 
		// We can display the user's username to them by reading it from the session array.  Remember that because 
		// a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 	

		<!-- Main CMS content -->
		<section id="cmswrapper">
			<section id="pagestructureWrapper">
				<aside id='page_tree'>
			<h2 class="h2header">MENU</h2>
			<ul>

		<?php
			$menu_items = mysql_query("SELECT * FROM vcup_menu ORDER BY _order");
			while ($item = mysql_fetch_array($menu_items)) {
				$id = $item['id'];
				echo '	<li class="menu_page"><h2 class="menuh2" id="' . $item['id'] . '">' . $item['name'] . '</h2>';
				#Check if a menu item may contain subpages						
				if ($item['expand'] == 1) {
					echo '<img class="createNewSubpage" id="' . $item['id'] . '" src="../assets/img/plus.PNG" />';
					$subs = mysql_query("SELECT * FROM vcup_subpages WHERE parent_id = $id ORDER BY _order ASC");
					$nSubs = mysql_num_rows($subs);

					echo '<span class="count_children">(<h6>' .$nSubs . '</h6>)</span>
							<ul class="subpages">';

					while ($sub_item = mysql_fetch_array($subs)) {
						echo '	<li><a href="cms.vcup_structure.php?editID=' . $sub_item['id'] . '" class="lnkSubpage">' . $sub_item['name'] . '</a></li>';
					}
					echo '	</ul>';
				} else {
					echo '<img class="locked" src="../assets/img/lock.PNG" />';
				}
				echo '	</li>';
			}
			echo ' 		<li class="menu_new_page"><h2 class="menuh2new">Nieuwe pagina</h2><img class="createNewMenuPage" src="../assets/img/plus.PNG" /></li>';	
		?>

			</ul>
		</aside>
		
				<section id="subpage_editor">
		
			<form action="" method="POST" id="editMenuItem">
				
				<?php
					if (isset($_POST['editMItem'])) {
						$id = mysql_real_escape_string($_POST['menuID']);
						$menu_name = mysql_real_escape_string($_POST['editMenuName']);
						$menu_order = mysql_real_escape_string($_POST['itemOrder']);
						$menu_expand = mysql_real_escape_string($_POST['itemExpand']);
						$menu_url = mysql_real_escape_string($_POST['menuURL']);
						
						$query = "UPDATE vcup_menu SET name='$menu_name', _order='$menu_order', expand='$menu_expand', url='$menu_url' WHERE id='$id'";
						mysql_query($query) or die();
						echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.vcup_structure.php">';	
					}
				?>
				
				
				<?php
					if (isset($_POST['createMenuItem'])) {
						$pagetitle = mysql_real_escape_string($_POST['editMenuName']);
						$order = mysql_real_escape_string($_POST['itemOrder']);
						$expand = mysql_real_escape_string($_POST['itemExpand']);
						$url = mysql_real_escape_string($POST['menuURL']);
						
						$query = "INSERT INTO vcup_menu (name, _order, expand, url) VALUES ('$pagetitle', '$order', '$expand', ''$url)";
						mysql_query($query) or die();
						echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.vcup_structure.php">';							
					}
				?>					
				
			</form>
			
			<form action="" method="POST" id="deleteMenuItem">
				
				<?php
					if (isset($_POST['deleteMItem'])) {
						$id = mysql_real_escape_string($_POST['menuID']);
						
						$query = "DELETE FROM vcup_menu WHERE id = '$id'";
						mysql_query($query) or die(); 
						echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.vcup_structure.php">';	
					}
				?>
				
			</form>
		
			<form action="" method="POST" id="createPage">
				
				<?php
					if (isset($_POST['addPage'])) {
						$parent_id = mysql_real_escape_string($_POST['parentID']);
						$pagetitle = mysql_real_escape_string($_POST['pageTitle']);
						$order = $mysqli->query("SELECT MAX(_order) AS max_order FROM subpages WHERE parent_id = '$parent_id'")->fetch_object()->max_order+1;
						
						$query = "INSERT INTO vcup_subpages (parent_id, _order, name, published) VALUES ('$parent_id', '$order', '$pagetitle', 0)";
						mysql_query($query) or die();
						echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.vcup_structure.php">';
					}
				?>
				
			</form>
		
			<form action="" method="POST" id="subPageEdit">
				
				<!-- Force display of form -->
				<script> $('#subPageEdit').show(); </script>
				
				<?php 
					if (isset($_GET['editID'])) {
						$id = mysql_real_escape_string($_GET['editID']);
						$query = mysql_query("SELECT * FROM vcup_subpages WHERE id = '$id' LIMIT 1");

						while ($page = mysql_fetch_array($query)) {		
							echo '	<h2 class="h2header">Wijzig subpagina</h2>		

									<input type="hidden" name="pageID" value="' . $page['id'] . '" />				
									
									<label for="subTitle" class="spTitle">Subpagina title: </label>
									<input type="text" name="subTitle" id="subTitle" maxlength="18" placeholder="E.g. Bestuur" value="' . $page['name'] . '" />
									
									<textarea class="ckeditor" name="page_editor" id="page_content">' . html_entity_decode($page['content']) . '</textarea>

									<label for="isPublished" class="spLabel">Online: </label>';
							if ($page['published']) {
								echo '	<input type="radio" name="isPublished" value="1" id="isPublished" CHECKED />Yes
										<input type="radio" name="isPublished" value="0" id="isPublished"  />No';
							} else {
								echo '	<input type="radio" name="isPublished" value="1" id="isPublished"/>Yes
										<input type="radio" name="isPublished" value="0" id="isPublished" CHECKED />No';
							}					
									
							echo '	<label for="pageOrder" class="lbPageOrder">Pagina volgorde: </label>
									<input type="text" name="pageOrder" id="pageOrder" size="2" maxlength="2" value="' . $page['_order'] . '" />
									
									<input type="submit" name="submitSubpage" id="submitSubpage" value="Wijzig" />
									<input type="submit" name="deleteSubpage" id="deleteSubpage" value="Verwijder" onclick="return confirm(\'Are your sure you want to delete this?\')"/>';
						}
							
				?>
					<script>
						CKEDITOR.replace( 'page_editor',
						{
							filebrowserBrowseUrl  :'<?=$config["absolute_path"]?>/ckeditor/plugins/image_browser.php',
							filebrowserUploadUrl  :'<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=File',
							filebrowserImageUploadUrl :'<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=Image',
						    filebrowserFlashUploadUrl : '<?=$config["absolute_path"]?>/ckeditor/plugins/filemanager/connectors/php/upload.php?Type=Flash'
						});											
					</script>		
				<?php							
						
						if (isset($_POST['deleteSubpage'])) {
							$id = mysql_real_escape_string($_POST['pageID']);
							$query = "DELETE FROM vcup_subpages WHERE id = '$id'";
							mysql_query($query) or die();
							echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.vcup_structure.php">';	
						}
						
						if (isset($_POST['submitSubpage'])) {
							if (get_magic_quotes_gpc()) {
								$content = htmlspecialchars(stripslashes($_POST['page_editor']));
							} else {
								$content = htmlspecialchars($_POST['page_editor']);
							}
							
							echo $content;
							
							$id = mysql_real_escape_string($_POST['pageID']);
							$order = mysql_real_escape_string($_POST['pageOrder']);
							$title = mysql_real_escape_string($_POST['subTitle']);
							$isPublished = mysql_real_escape_string($_POST['isPublished']);
							
							$query = "UPDATE vcup_subpages SET _order='$order', name='$title', published=$isPublished, content='$content' WHERE id=$id";
							mysql_query($query) or die();
							echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.vcup_structure.php">';										
						}						
					}
				?>			
			
			</form>
		
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