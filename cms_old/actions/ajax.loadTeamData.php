<?php 
	require_once('../../assets/config.php');

	function getMatches($team_id) {
		global $mysqli;
		$current_competition = $mysqli->query("SELECT competition_id FROM competition WHERE team_id = '$team_id' AND current = 1")->fetch_object()->competition_id;
		$team_matches = mysql_query("SELECT * FROM matches WHERE team_id = '$team_id' AND competition_id = '$current_competition' ORDER BY match_date");
		$team_name = mysql_result(mysql_query("SELECT team_name FROM teams WHERE team_id = '$team_id' LIMIT 1"), 0);
		$nMatches = mysql_num_rows($team_matches);
		
		echo '		<table class="team_matches">
						<tr>
							<th>Datum</th>
							<th>Tijd</th>
							<th>Teams</th>
							<th>Locatie</th>
							<th>Goals</th>
							<th></th>
							<th></th>
						</tr>';		
		
		if ($nMatches > 0) {
				
			while ($match = mysql_fetch_array($team_matches)) {
				echo '	<tr>
							<td class="clmn_match_date">' . date('j M', strtotime($match['match_date'])) . '</td>
							<td class="clmn_match_time">' . date('G:i', strtotime($match['match_time'])) . '</td>';
				if ($match['home']) { 
					echo ' 	<td class="clmn_match_teams">' . $team_name . ' - ' . $match['opponent'] . '</td>';
				} else {
					echo ' 	<td class="clmn_match_teams">' . $match['opponent'] . ' - ' . $team_name . '</td>';
				}
				echo '		<td class="clmn_match_location">' . $match['location'] . '</td>';
				if ($match['played']) {
					//echo '	<td class="clmn_match_result">' . $match['home_goals'] . ' - ' . $match['away_goals'] . '</td>';
					echo '	<td class="clmn_match_goals"><input type="text" class="home_goals" value="' . $match['home_goals'] . '" /> - <input type="text" class="away_goals" value="' . $match['away_goals'] . '"</td>'; 
				} else {
					echo '	<td class="clmn_match_goals"><input type="text" class="home_goals" /> - <input type="text" class="away_goals" /></td>';
				}
				//if (!$match['played']) {
					echo '	<td>
								<button type="submit" name="updateMatchScore" class="btn_edit" id="updateMatchScore" value="' . $match['id'] . '"></button>
							</td>';
				//}
					echo '	<td class="clmn_match_delete">
								<form action="" method="POST">
									<button type="submit" name="deleteMatch" class="btn_remove" value="' . $match['id'] .'" onclick="return confirm(\'Weet je zeker dat je deze wedstrijd wilt verwijderen?\')"></button>
								</form>

							</td>
							<td></td>';
			}
		} else {
			echo '		<tr>
							<td colspan="7">Er zijn geen wedstrijden bekend voor dit team.</td>
						</tr>';
		}
			echo '	</table>';
			
		echo '	<button class="btn_add_new_match">Nieuwe wedstrijd</button>
				<button type="submit" name="deleteTeam" class="btn_delete_team" id="' . $team_id . '">Verwijder team</button>';
			
		echo '	<!-- From for adding new matches to the current competition-->
				<form action="" method="POST" id="form_add_match">
					
					<h3>Creëer nieuwe wedstrijd</h3>
				
					<input type="hidden" name="team_id" class="team_id" value="' . $team_id . '" />
					<input type="hidden" name="competition_id" value="' . $current_competition . '" />
					
					<label for="match_date">Datum: </label>
					<input type="text" name="match_date" class="match_date" placeholder="04-04-1990"/>
					
					<br />
					
					<label for="match_time">Tijd: </label>
					<input type="time" name="match_time" class="match_time" placeholder="14:30"/>
					
					<br />
					
					<label for="match_opponent">Tegenstander: </label>
					<input type="text" name="match_opponent" class="match_opponent" />
					
					<br />
					
					<label for="match_location">Locatie: </label>
					<input type="text" name="match_location" class="match_location" />
					
					<input type="radio" name="home_or_away" value="1" CHECKED>Thuis
					<input type="radio" name="home_or_away" value="0">Uit
					
					<br />
					
					<input type="submit" name="submit_new_match" class="submit_new_match" value="Voeg toe" />
					
				</form>';

	}

	function loadTeamInfo($team_id) {
		echo '	<!-- Select the current competition -->
				<section id="manage_team_competition">
					<h3>HUIDIGE COMPETITIE</h3>			
					<label for="select_current_competition">Selecteer competitie</label>
					<select name="select_current_competition" class="select_current_competition">';

					$competitions = mysql_query("SELECT * FROM competition WHERE team_id = '$team_id' ORDER BY current DESC");
					while ($competition = mysql_fetch_array($competitions)) {
			
						#Parse competition info
						if ($competition['type'] == '0') { $type = 'Veld'; }
						else { $type = 'Zaal'; }
						$start_date = strtotime($competition['start_date']);
						$end_date = strtotime($competition['end_date']);
			
						echo '<option id="' . $competition['team_id'] . '" value="' . $competition['competition_id'] . '">' . $type . ', ' . date('j M Y', $start_date) . ' - ' . date('j M Y', $end_date) . '</option>';
					}

			echo '	</select>
	
					<button id="btn_new_competition">Nieuwe competitie</button>
					<button class="btn_delete_competition">Verwijder competitie</button>
					
					<!-- Create a new competition -->
					<form action="" method="POST" id="create_competition">
						
						<h3>Creëer nieuwe competitie</h3>
						<input type="hidden" name="select_team" value="' . $team_id . '" />

						<label for="competition_select_type" id="lbl_competition_select_type">Type: </label>
						<select name="competition_select_type" id="competition_select_type">
							<option value="1">Zaal</option>
							<option value="0">Veld</option>
						</select>
			
						<br />
			
						<label for="competition_start_date" id="lbl_competition_start_date">Start datum: </label>
						<input type="text" name="competition_start_date" class="date" id="competition_start_date" placeholder="04-04-1990" />
			
						<br />
			
						<label for="competition_end-date" id="lbl_competition_end_date">Eind datum: </label>
						<input type="text" name="competition_end_date" class="date" id="competition_end_date" placeholder="04-04-1990" />
			
						<br />
			
						<input type="submit" name="submit_competition" id="submit_competition" value="Voeg toe" />
			
					</form>
				
				</section>';

		getMatches($team_id);
	}

	# Update current competition
	if (isset($_POST['team_id']) AND isset($_POST['competition_id'])) {
		$team_id = mysql_real_escape_string($_POST['team_id']);
		$competition_id = mysql_real_escape_string($_POST['competition_id']);
		
		mysql_query("UPDATE competition SET current = 1 WHERE team_id = '$team_id' AND competition_id = '$competition_id'") or die(mysql_error());
		mysql_query("UPDATE competition SET current = 0 WHERE team_id = '$team_id' AND competition_id <> '$competition_id'") or die(mysql_error());
	}

	# Show selected team information
	if (isset($_POST['team_id'])) {
		$team_id = mysql_real_escape_string($_POST['team_id']);
		loadTeamInfo($team_id);
	}
	
	# Delete competition
	if (isset($_POST['team_id']) AND isset($_POST['delete_competition'])) {
		$team_id = mysql_real_escape_string($_POST['team_id']);
		$competition_id = mysql_real_escape_string($_POST['delete_competition']);
		mysql_query("DELETE FROM competition WHERE competition_id = '$competition_id'") or die(mysql_error());
		mysql_query("DELETE FROM matches WHERE competition_id = '$competition_id'") or die(mysql_error());
	}	
	
	# Delete team
	if (isset($_POST['delete_team'])) {
		$team_id = mysql_real_escape_string($_POST['delete_team']);
		mysql_query("DELETE FROM teams WHERE team_id = '$team_id'") or die(mysql_error());
		mysql_query("DELETE FROM competition WHERE team_id = '$team_id'") or die(mysql_error());
		mysql_query("DELETE FROM matches WHERE team_id = '$team_id'") or die(mysql_error());		
	}
	
	# Set match scores
	if (isset($_POST['match_id']) AND isset($_POST['home_goals']) AND isset($_POST['away_goals'])) {
		$match_id = mysql_real_escape_string($_POST['match_id']);
		$home_goals = mysql_real_escape_string($_POST['home_goals']);
		$away_goals = mysql_real_escape_string($_POST['away_goals']);
		mysql_query("UPDATE matches SET home_goals = '$home_goals', away_goals = '$away_goals', played = 1 WHERE id = '$match_id'") or die(mysql_error());

		$team_id = $mysqli->query("SELECT team_id FROM matches WHERE id = '$match_id'")->fetch_object()->team_id;
		loadTeamInfo($team_id);

	}	
?>