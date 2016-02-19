<?php 
	require_once('../../assets/config.php');

	# Update current competition
	if (isset($_POST['team_id']) AND isset($_POST['competition_id'])) {
		$team_id = mysql_real_escape_string($_POST['team_id']);
		$competition_id = mysql_real_escape_string($_POST['competition_id']);
		
		mysql_query("UPDATE competition SET current = 1 WHERE team_id = '$team_id' AND competition_id = '$competition_id'") or die(mysql_error());
		mysql_query("UPDATE competition SET current = 0 WHERE team_id = '$team_id' AND competition_id <> '$competition_id'") or die(mysql_error());
	}

?>