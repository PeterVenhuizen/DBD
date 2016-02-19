<?php require_once('../assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Agenda</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >		
		<script type='text/javascript'>
			$(document).ready(function() {

				$('#addEvent').hide();
				$('.btn_new_activity').click(function() {
					$('#addEvent').toggle();
					if ($(this).html() == 'Verberg') { $(this).html('Nieuwe activiteit'); }
					else { $(this).html('Verberg'); }
				});

				// Enable/disable end date field
				var setEndDate = function() {
					if ($("#useEndDate").is(":checked")) {
						$('#end_date').prop('disabled', false);
					}
					else {
						$('#end_date').prop('disabled', 'disabled');
					}
				};

				$(setEndDate);
				$("#useEndDate").change(setEndDate);
								
				// Enable/disable time field
				var setStartTime = function() {
					if ($('#useStartTime').is(":checked")) {
						$('#time').prop('disabled', false);
					}
					else {
						$('#time').prop('disabled', 'disabled');
					}
				};
				
				$(setStartTime);
				$('#useStartTime').change(setStartTime);

				// Check if field is empty
				jQuery.fn.extend({
					validate_not_empty: function() {
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
				
				// Check if date is valid
				jQuery.fn.extend({
					is_valid_date: function() {
						var isValid = false;
						
						var date = $(this).val();
						var splitDate = date.split('-')
						
						var day = parseInt(splitDate[0]);
						var month = parseInt(splitDate[1]);
						var year = parseInt(splitDate[2]);
						
						// Check if it is a leap year
						var isLeap = new Date(year, 1, 29).getMonth() == 1
						
						//Check date
						var pattern = new RegExp('[1|2][0-9]{3}');
						if (year.toString().length == 4 && pattern.test(year)) {						
							if (day <= 31 && $.inArray(month, [1, 3, 5, 7, 8, 10, 12]) != -1) {
								isValid = true;
							} else if (day <= 30 && $.inArray(month, [4, 6, 9, 11]) != -1) {
								isValid = true;
							} else if (day == 29 && month == 2 && isLeap) {
								isValid = true;
							} else if (day <= 28 && month == 2) {
								isValid = true;	
							} 
						}

						// Add field formatting
						if (isValid) {
							$(this).removeClass('error');
							$(this).addClass('correct');
						} else {
							$(this).removeClass('correct');
							$(this).addClass('error');						
						}

						return isValid
					}
				})
				
				// Check if time is in a valid format
				jQuery.fn.extend({
					is_valid_time: function() {
						var time = $(this).val();
						hours = parseInt(time.split(':')[0]);
						minutes = parseInt(time.split(':')[1]);
						
						isValid = false;
						if (hours <= 23 && minutes <= 59) {
							isValid = true;
						} else if (hours == 24 && minutes == 0) {
							isValid = true;
						}
						
						// Add field formatting
						if (isValid) {
							$(this).removeClass('error');
							$(this).addClass('correct');
						} else {
							$(this).removeClass('correct');
							$(this).addClass('error');						
						}
						
						return isValid;
					}
				})
				
				// Validate if fields are empty
				$('#activity, #location').focusout(function() {
					$(this).validate_not_empty();
				});
				
				// Validate if a valid date is inserted
				$('#start_date, #end_date').focusout(function() {
					$(this).is_valid_date();
				});
				
				// Validate if valid time is inserted
				$('#time').focusout(function() {
					$(this).is_valid_time();
				});
				
				// ON SUBMIT: Check edit menu item
				$(document).on('click', '#submitEvent', function(e) {
					if (!$('#activity, #location').validate_not_empty()) {
						e.preventDefault();
					} else if (!$('#start_date').is_valid_date() || ($("#useEndDate").is(":checked") && !$('#end_date').is_valid_date())) {
						e.preventDefault();			
					} else if ($('#useStartTime').is(":checked") && !$('#time').is_valid_time()) {
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
    	echo 'You don\'t have the rights to view and edit this page.';
    	echo '<a href="cms.logout.php">Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 
	 
		// We can display the user's username to them by reading it from the session array.  Remember that because 
		// a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 			
			<section id="agendaWrapper">
				
				<div id="agenda_explanation">
					<h2>Agenda uitleg</h2>
					<p>Op deze pagina kun je nieuwe activiteiten toevoegen aan de agenda. Er zijn drie verschillende soorten activiteiten: trainingen, toernooien en diversen. De wedstrijden worden automatisch in de agenda geplaatst, zodra deze bij "Competitie" zijn ingevuld.</p>
				</div>

				<button class="btn_new_activity">Nieuwe activiteit</button>
	
				<form action="" method="POST" id="addEvent">
			
					<h3>Creëer nieuwe activiteit</h3>
			
					<label for="activity">Activiteit:</label>
					<input type="text" name="activity" id="activity" />

					<select name="category" id="category">
						<option value="practice">Training</option>
						<!--<option value="match">Wedstrijd</option>-->
						<option value="tournament">Toernooi</option>
						<option value="misc">Diverse</option>																
					</select>

					<br />

					<label for="location">Locatie: </label>
					<input type="text" name="location" id="location" />

					<br />
			
					<label for="start_date">Start datum: </label>
					<input type="text" name="start_date" id="start_date" placeholder="dd-mm-jjjj"/>	
			
					<br />
			
					<input type="checkbox" id="useEndDate" name="useEndDate" value="yes" />
					<label for="end_date" class="end_date indent">Eind datum: </label>
					<input type="text" name="end_date" id="end_date" placeholder="dd-mm-jjjj"/>
			
					<br />

					<input type="checkbox" id="useStartTime" name="useStartTime" value="yes"/>			
					<label for="time" class="time indent">Tijd: </label>
					<input type="time" name="time" id="time" maxlength="5" placeholder="18:00"/>

					<br />
			
					<input type="submit" name="submitEvent" id="submitEvent" value="Voeg toe!" />
			
					<?php
						if (isset($_POST['submitEvent'])) {
							$activity = mysql_real_escape_string($_POST['activity']);
							$category = mysql_real_escape_string($_POST['category']);
							$location = mysql_real_escape_string($_POST['location']);
							$start_date = mysql_real_escape_string($_POST['start_date']);
							$mysql_start_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-',$start_date)));
					
							if ($_POST['useEndDate'] == "yes") {
								$end_date = mysql_real_escape_string($_POST['end_date']);
								$mysql_end_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-',$end_date)));
							} else { $mysql_end_date === NULL; }

							if ($_POST['useStartTime'] == "yes") { $time = mysql_real_escape_string($_POST['time']); }
							else { $time === NULL;}
		
							if ($mysql_end_date == NULL) {
								if ($time == NULL) {
									$query = "INSERT INTO agenda (start_date, end_date, time, event, location, type) VALUES ('$mysql_start_date', NULL, NULL, '$activity', '$location', '$category')";
								} else {
									$query = "INSERT INTO agenda (start_date, end_date, time, event, location, type) VALUES ('$mysql_start_date', NULL, '$time', '$activity', '$location', '$category')";
								}
							} else if ($time == NULL) {
								$query = "INSERT INTO agenda (start_date, end_date, time, event, location, type) VALUES ('$mysql_start_date', '$mysql_end_date', NULL, '$activity', '$location', '$category')";
							} else {
								$query = "INSERT INTO agenda (start_date, end_date, time, event, location, type) VALUES ('$mysql_start_date', '$mysql_end_date', '$time', '$activity', '$location', '$category')";
							}
					
							mysql_query($query) or die();
						}
					?>
			
				</form>
				
				<table id="agenda">
					<tr>
						<th>Activiteit</th>
						<th>Datum</th>
						<th>Tijd</th>
						<th>Locatie</th>
						<th></th>
					</tr>
					<?php
						$agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date ORDER BY start_date");
						while ($event = mysql_fetch_array($agenda)) {
							$event_startdate = strtotime($event['start_date']);
							if ($event['end_date'] != '') { $event_enddate = strtotime($event['end_date']); };
							$event_time = strtotime($event['time']);
							echo '	<tr>
										<td class="columnEvent">' . $event['event'] . '</td>
										<td class="columnDate">' . date('j M', $event_startdate); if ($event['end_date'] != '') { echo " - " . date('j M', $event_enddate); } else { echo ''; } echo '</td>
										<td class="columnTime">'; if ($event['time'] != '') { echo date('G:i', $event_time); } echo '</td>
										<td class="columnLocation">' . $event['location'] . '</td>
										<td class="columnDelete">
											<form action="" method="POST">
												<button type="submit" name="deleteEvent" class="btn_remove" value="' . $event['id'] .'" onclick="return confirm(\'Weet je zeker dat je dit event wilt verwijderen?\')"></button>
											</form>
										</td>
									</tr>';
						}
				
						// Delete agenda event
						if (isset($_POST['deleteEvent'])) {
							$id = mysql_real_escape_string($_POST['deleteEvent']);
							$remove_query = "DELETE FROM agenda WHERE id=$id";
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
		
			</section>

		<!-- Footer -->
		<?php include('cms.footer.php'); ?>

		</section>
	</body>
</html>
<?php
	}
?>