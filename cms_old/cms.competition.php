<?php require_once('../assets/config.php'); ?>

<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Competitie</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<script type='text/javascript'>
			$(document).ready(function() {

				// Toggle add team view and button text
				$('#add_team').hide();
				$('#btn_new_team').click(function() {
					$('#add_team').toggle();
					if ($(this).html() == 'Verberg') { $(this).html('Nieuw team'); }
					else { $(this).html('Verberg'); }
				});				

				// Toggle add competition view and button text
				$(document).ajaxComplete(function() {
					$('#create_competition').hide();					
				});			
				$(document).on('click', '#btn_new_competition', function() {
					$('#create_competition').toggle();
					if ($(this).html() == 'Verberg') { $(this).html('Nieuwe competitie'); }
					else { $(this).html('Verberg'); }				
				});

				// Toggle view for new match form
				$(document).ajaxComplete(function() {
					$('#form_add_match').hide();
				});
				$(document).on('click', '.btn_add_new_match', function() {
					$('#form_add_match').toggle();
					if ($(this).html() == 'Verberg') { $(this).html('Nieuwe wedstrijd'); }
					else { $(this).html('Verberg'); }
				});

				// Handle match result
				$(document).on('click', '#updateMatchScore', function() {
					var home_goals = $(this).parent().prev().find('input.home_goals').val();
					var away_goals = $(this).parent().prev().find('input.away_goals').val();
					if ($.isNumeric(home_goals) && $.isNumeric(away_goals)) {
						var match_id = $(this).attr('value');
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.loadTeamData.php',
							data: 'match_id='+match_id+'&home_goals='+home_goals+'&away_goals='+away_goals,
							success: function(result) {
								$('#team_data').html(result);
							}
						})
					} else {
						alert("Een van de scores is niet numeriek!");
					}
				});
		
				//Load first team on page load
				var first_team = $('.btn_team:first').attr('id');
				$('.btn_team:first').css({ color: '#FFF' });
				$.ajax({
					type: 'POST',
					url: 'actions/ajax.loadTeamData.php',
					data: 'team_id='+first_team,
					success: function(result) {
						$('#team_data').html(result);
					}
				});			
		
				//Change team on click
				$('.btn_team').click(function() {
					var team_id = $(this).attr('id');
					$('.btn_team').css({
						color: '#000'
					});
					$(this).css({
						color: '#FFF'
					});
					$.ajax({
						type: 'POST',
						url: 'actions/ajax.loadTeamData.php',
						data: 'team_id='+team_id,
						success: function(result) {
							$('#team_data').html(result);
						}
					})
				});
			
				//On competition change
				$(document).on('change', '.select_current_competition', function() {
					var team_id = $('option:selected', this).attr('id');
					var competition_id = $('option:selected', this).attr('value');
					$.ajax({
						type: 'POST',
						url: 'actions/ajax.loadTeamData.php',
						data: 'team_id='+team_id+'&competition_id='+competition_id,
						success: function(result) {
							$('#team_data').html(result);
						}
					})
				});	
				
				// Delete competition
				$(document).on('click', '.btn_delete_competition', function() {
					var competition_id = $('option:selected', '.select_current_competition').attr('value');
					var team_id = $('option:selected', '.select_current_competition').attr('id');
					if (confirm('Weet je zeker dat je deze competitie wilt verwijderen?')) {
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.loadTeamData.php',
							data: 'team_id='+team_id+'&delete_competition='+competition_id,
							success: function(result) {
								$('#team_data').empty();
								$('#team_data').html(result);
							}
						})
					}
				});
				
				// Delete team
				$(document).on('click', '.btn_delete_team', function() {
					var team_id = $(this).attr('id');
					if (confirm('Weet je zeker dat je dit team wilt verwijderen?')) {
						$.ajax({
							type: 'POST',
							url: 'actions/ajax.loadTeamData.php',
							data: 'delete_team='+team_id,
							success: function(result) {
								$('#team_data').html(result);
							}
						})
					}				
				});		

				// Check if field is empty
				jQuery.fn.extend({
					is_not_empty: function() {
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
				
				/* Add team validation */
				// Validate if the team name is not empty
				$('#new_team_name').focusout(function() {
					$(this).is_not_empty();
				});
				
				// ON SUBMIT: Check if team name is not empty
				$(document).on('click', '#submit_new_team', function(e) {
					if (!$('#new_team_name').is_not_empty()) {
						e.preventDefault();
					}
				});			
					
				/* Add competition validation */
				// Validate if a valid date is inserted
				$(document).on('focusout', '#competition_start_date, #competition_end_date', function() {
					$(this).is_valid_date();
				});

				// ON SUBMIT: Check dates of new competition
				$(document).on('click', '#submit_competition', function(e) {
					if (!$('#competition_start_date').is_valid_date() || !$('#competition_end_date').is_valid_date()) {
						e.preventDefault();
					} 
				});
	
				/* Add match validation */
				// Validate if a valid date is inserted
				$(document).on('focusout', '.match_date', function() {
					$(this).is_valid_date();
				}); 	
				// Validate if a valid time is inserted
				$(document).on('focusout', '.match_time', function() {
					$(this).is_valid_time();
				});
				// Validate if fields are not empty
				$(document).on('focusout', '.match_opponent, .match_location', function() {
					$(this).is_not_empty();
				});
				
				// ON SUBMIT: Check all match fields
				$(document).on('click', '.submit_new_match', function(e) {
					if (!$('.match_date').is_valid_date() || !$('.match_time').is_valid_time() || !$('.match_opponent').is_not_empty() || !$('.match_location').is_not_empty()) {
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
    
	if ($_SESSION['user']['rights'] < 2) {
    	echo 'Je beschikt niet over de vereiste rechten om deze pagina te bewerken! <a href="cms.logout.php"> Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 
	 
		// We can display the user's username to them by reading it from the session array.  Remember that because 
		// a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 	
			
		<!-- Main CMS content -->
		<section id="cmswrapper">
		
			<section id="competition">
				<section id="teams">
				<!-- Overview of all teams and add new team form -->
			
				<?php
					$teams = mysql_query("SELECT * FROM teams GROUP BY team_name");
					while ($team = mysql_fetch_array($teams)) {
						echo '<button id="' . $team['team_id'] . '" class="btn_team">' . $team['team_name'] . '</button>';
					}
				?>
					<button id="btn_new_team">Nieuw team</button>
				
					<form action="" method="POST" id="add_team">
						<h3>Creëer nieuw team</h3>
						<label for="team_name">Team naam: </label>
						<input type='text' placeholder='bv. Midweek 1' name='new_team_name' id='new_team_name' />
						<input type='submit' name='submit_new_team' value='Creëer team' id='submit_new_team' />
			
					<?php
						if (isset($_POST['submit_new_team'])) {
							$team_name = mysql_real_escape_string($_POST['new_team_name']);
				
							#Check if name already exists
							$name_check = mysql_query("SELECT * FROM teams WHERE team_name = '$team_name'");
							$num_rows = mysql_num_rows($name_check);
							if ($num_rows != 0) {
								?>
									<div id='name_exists'>Deze team naam is al in gebruik, kies een andere naam!</div>
								<?php
							} else {
								$query = "INSERT INTO teams (team_name) VALUES ('$team_name')";
								mysql_query($query) or die(mysql_error());
							}
						}
					?>						
					</form>
				
				</section>
	
				<section id="team_data">
					<!-- All data from this team (competition, matches, results, etc) -->
			
					<!-- Handle competition submit -->
					<?php	
						if (isset($_POST['submit_competition'])) {
							$team_id = mysql_real_escape_string($_POST['select_team']);
							$competition_type = mysql_real_escape_string($_POST['competition_select_type']);
							$start_date = mysql_real_escape_string($_POST['competition_start_date']);
							$mysql_start_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-',$start_date)));
							$end_date = mysql_real_escape_string($_POST['competition_end_date']);
							$mysql_end_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-',$end_date)));
					
							$query = "INSERT INTO competition (team_id, type, start_date, end_date, current) VALUES ('$team_id', '$competition_type', '$mysql_start_date', '$mysql_end_date', '1')";
							mysql_query($query) or die(mysql_error());
						}
					?>	
				
					<!-- Manage matches -->	
					<?php	
			
						# Add match
						if (isset($_POST['submit_new_match'])) {
							$team_id = mysql_real_escape_string($_POST['team_id']);
							$competition_id = mysql_real_escape_string($_POST['competition_id']);
							$match_date = mysql_real_escape_string($_POST['match_date']);
							$mysql_match_date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $match_date)));
							$match_time = mysql_real_escape_string($_POST['match_time']);
							$match_opponent = mysql_real_escape_string($_POST['match_opponent']);
							$match_location = mysql_real_escape_string($_POST['match_location']);
							$home_or_away = mysql_real_escape_string($_POST['home_or_away']);	
		
							$query = "INSERT INTO matches (team_id, competition_id, match_date, match_time, opponent, location, home) VALUES ('$team_id', '$competition_id', '$mysql_match_date', '$match_time', '$match_opponent', '$match_location', '$home_or_away')";
							mysql_query($query) or die(mysql_error());
								
						}				
				
						// Delete match
						if (isset($_POST['deleteMatch'])) {
							$id = mysql_real_escape_string($_POST['deleteMatch']);
							$remove_query = "DELETE FROM matches WHERE id=$id";
							mysql_query($remove_query) or die();
					?>		
							<!-- Reload page after event is deleted -->
							<script>
								location.reload();
							</script>					
					<?php		
						}
					?>
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