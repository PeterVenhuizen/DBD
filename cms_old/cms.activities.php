<?php require_once('../assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Activiteiten</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type='text/javascript' src='../assets/js/validation_functions.js'></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<script type='text/javascript'>
			$(document).ready(function() {
				$('#addActivity').hide();
				$('.btn_new_activity').click(function() {
					$('#addActivity').toggle();
					if ($(this).html() == 'Verberg') { $(this).html('Nieuwe activiteit'); }
					else { $(this).html('Verberg'); }
				});		
				
				// Validate if fields are empty
				$(document).on('focusout', '#activity, #description, #eactivity, #edescription', function() {
					$(this).is_not_empty();
				});
				
				// Validate if a valid date is inserted
				$(document).on('focusout', '#date, #edate', function() {
					$(this).is_valid_date();
				});
				
				// ON SUBMIT: Check activity fields
				$(document).on('click', '#submitActivity', function(e) {
					if (!$('#activity').is_not_empty() || !$('#description').is_not_empty() || !$('#date').is_valid_date()) {
						e.preventDefault();
					}
				});			

				// ON SUBMIT: Check edited activity fields				
				$(document).on('click', '#submitEditActivity', function(e) {
					if (!$('#eactivity').is_not_empty() || !$('#edescription').is_not_empty() || !$('#edate').is_valid_date()) {
						e.preventDefault();
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
    	echo 'Je beschikt niet over de vereiste rechten om deze pagina te bewerken! <a href="cms.logout.php"> Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 
	 
		// We can display the user's username to them by reading it from the session array.  Remember that because 
		// a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 			
			<section id="activitiesWrapper">
				
				<div id="activities_explanation">
					<h2>Activiteiten uitleg</h2>
					<p>Op deze pagina kunnen nieuwe activiteiten worden aangemaakt, waarvoor geregistreerde leden zich kunnen aanmelden. Geef simpelweg de activiteit een naam, datum en beschrijving en bepaal of de activiteit open is voor inschrijving. Indien je witregels toe wil voegen aan de beschrijving, maak dan gebruik van de <a href="http://www.w3schools.com/tags/tag_br.asp" target="blank">br-tag</a>.</p>
				</div>

<?php
	if (isset($_GET['actID'])) {
		$id = mysql_real_escape_string($_GET['actID']);
		$query = mysql_query("SELECT * FROM events WHERE id = '$id' LIMIT 1") or die(mysql_error());
		while ($row = mysql_fetch_assoc($query)) {
			echo '	<form action="" method="POST" id="editActivity">
			
						<h3>Bewerk activiteit</h3>
			
						<label for="eactivity">Activiteit:</label>
						<input type="text" name="eactivity" id="eactivity" value="' . $row['title'] . '"/>

						<br />
			
						<label for="edate">Datum: </label>
						<input type="text" name="edate" id="edate" placeholder="dd-mm-jjjj" value="' . date('d-m-Y', strtotime($row['edate'])) . '"/>	
			
						<br />
			
						<label for="edescription">Beschrijving: </label>
						<textarea name="edescription" id="edescription">' . $row['description'] . '</textarea>

						<br />
					
						<label for="status">Is online: </label>';
						if ($row['active']) { 
							echo '	<input type="radio" name="estatus" value="1" CHECKED>Ja
									<input type="radio" name="estatus" value="0">Nee'; 
						} else { 
							echo '	<input type="radio" name="estatus" value="1">Ja
									<input type="radio" name="estatus" value="0" CHECKED>Nee';
						}
						
			echo '		<br />
			
						<input type="submit" name="submitEditActivity" id="submitEditActivity" value="Wijzig!" />';
			
						if (isset($_POST['submitEditActivity'])) {
							$activity = mysql_real_escape_string($_POST['eactivity']);
							$date = mysql_real_escape_string($_POST['edate']);
							$mysql_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-', $date)));
							$description = mysql_real_escape_string($_POST['edescription']);
							$active = mysql_real_escape_string($_POST['estatus']);

							$query = "UPDATE events SET title = '$activity', edate = '$mysql_date', description = '$description', active = '$active' WHERE id = '$id'";
							mysql_query($query) or die();
							echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.activities.php">';
						}
			
			echo '		</form>';
		}
	}
?>

				<button class="btn_new_activity">Nieuwe activiteit</button>
	
				<form action="" method="POST" id="addActivity">
			
					<h3>Creëer nieuwe activiteit</h3>
			
					<label for="activity">Activiteit:</label>
					<input type="text" name="activity" id="activity" />

					<br />
			
					<label for="date">Datum: </label>
					<input type="text" name="date" id="date" placeholder="dd-mm-jjjj"/>	
			
					<br />
			
					<label for="description">Beschrijving: </label>
					<textarea name="description" id="description"></textarea>

					<br />
					
					<label for="status">Is online: </label>
					<input type="radio" name="status" value="1" CHECKED>Ja
					<input type="radio" name="status" value="0">Nee
					
					<br />
			
					<input type="submit" name="submitActivity" id="submitActivity" value="Voeg toe!" />
			
					<?php
						if (isset($_POST['submitActivity'])) {
							$activity = mysql_real_escape_string($_POST['activity']);
							$date = mysql_real_escape_string($_POST['date']);
							$mysql_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-', $date)));
							$description = mysql_real_escape_string($_POST['description']);
							$active = mysql_real_escape_string($_POST['status']);

							$query = "INSERT INTO events (title, edate, description, active) VALUES ('$activity', '$mysql_date', '$description', '$active')";
							mysql_query($query) or die();
							echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.activities.php">';
						}
					?>
			
				</form>	


				<table id="activities">
					<tr>
						<th>Activiteit</th>
						<th>Datum</th>
						<th>Beschrijving</th>
						<th>Aanwezigen</th>
						<th>Online</th>
						<th></th>
						<th></th>
					</tr>
					<?php
						$activities = mysql_query("SELECT * FROM events WHERE CURRENT_DATE() <= edate ORDER BY edate");
						while ($activity = mysql_fetch_array($activities)) {
							$subscribers = '';
							if ($activity['subscribers'] != '') {
								$subscriber_ids = explode(';', $activity['subscribers']);
								foreach($subscriber_ids as &$id) {
									$query = mysql_query("SELECT first_name, prefix, last_name FROM users WHERE id = '$id'");
									while ($row = mysql_fetch_assoc($query)) {
										$subscribers .= $row['first_name'] . ' ' . $row['prefix'] . ' ' . $row['last_name'] . ' ';
									}
								}
							}						
							echo '	<tr>
										<td class="actTitle">' . $activity['title'] . '</td>
										<td class="actDate">' . date('j M', strtotime($activity['edate'])) . '</td>
										<td class="actDescription">' . $activity['description'] . '</td>
										<td class="actSubscribers">' . $subscribers . '</td>
										<td class="actActive">'; if ($activity['active']) { echo 'Ja'; } else { echo 'Nee'; } echo  '</td>
										<td class="actEdit">
											<a href="cms.activities.php?actID=' . $activity['id'] . '" class="btn_edit"></a>
										</td>
										<td class="actDelete">
											<form action="" method="POST">
												<button type="submit" name="deleteAct" class="btn_remove" value="' . $activity['id'] .'" onclick="return confirm(\'Weet je zeker dat je deze activiteit wilt verwijderen?\')"></button>
											</form>
										</td>
									</tr>';
						}
				
						// Delete agenda event
						if (isset($_POST['deleteAct'])) {
							$id = mysql_real_escape_string($_POST['deleteAct']);
							$remove_query = "DELETE FROM events WHERE id=$id";
							mysql_query($remove_query) or die();
							echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.activities.php">';
						}
				
					?>
				</table>								
		
			</section>

		<!-- Footer -->
		<?php include('cms.footer.php'); ?>

		</section>
	</body>
</html>
<?php
	}
?>