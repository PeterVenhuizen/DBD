<?php 
	require_once('../config.php');

	if (isset($_POST['agenda'])) {
		$selection = $_POST['agenda'];
		
		if ($selection == '*') {
			$agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND CURRENT_DATE <= match_date ORDER BY start_date");
		} else if ($selection == 'match') {
			$agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date AND type = '$selection' UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND CURRENT_DATE <= match_date ORDER BY start_date"); 			
		} else {
			$agenda = mysql_query("SELECT * FROM agenda WHERE (CURRENT_DATE() <= start_date OR CURRENT_DATE() BETWEEN start_date AND end_date) AND type = '$selection' ORDER BY start_date");
		}
		$nEvents = mysql_num_rows($agenda);

		if ($nEvents > 0) {
			while ($event = mysql_fetch_array($agenda)) {
				$event_startdate = strtotime($event['start_date']);
				if ($event['end_date'] != '') { $event_enddate = strtotime($event['end_date']); };
				$event_time = strtotime($event['time']);
				echo '	<tr>
							<td class="date">' . date('j M', $event_startdate); if ($event['end_date'] != '') { echo " - " . date('j M', $event_enddate); } else { echo ''; } echo '</td>				
							<td class="time">'; if ($event['time'] != '') { echo date('G:i', $event_time); } echo '</td>
							<td class="event">' . $event['event'] . '</td>
							<td class="location">' . $event['location'] . '</td>
						</tr>';
			}
		} else {
			echo '		<tr>
							<td colspan="4">Geen activiteiten voor deze categorie gevonden. </td>
						</tr>';
		}
	}
?>
