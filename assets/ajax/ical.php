<?php 
	require_once('../config.php');

	if (isset($_GET['agenda'])) {
		$selection = $_GET['agenda'];
		
		if ($selection == '*') {
			$agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND CURRENT_DATE <= match_date ORDER BY start_date");
		} else if ($selection == 'match') {
			$agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date AND type = '$selection' UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND CURRENT_DATE <= match_date ORDER BY start_date");
		} else {
			$agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date AND type = '$selection'");
		}
		
		$nEvents = mysql_num_rows($agenda);
		if ($nEvents > 0) {

			$ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//WSKV Debalderin//Agenda//NL\n";

			while ($event = mysql_fetch_array($agenda)) {
				$event_startdate = strtotime($event['start_date']);
				if ($event['end_date'] != '') { 
					if ($selection == 'tournament') {
						$event_enddate = strtotime($event['end_date'] . ' + 1 days');
					} else {
						$event_enddate = strtotime($event['end_date']); 
					}
				} else {
					$event_enddate = $event_startdate;
				}
				$event_time = strtotime($event['time']);		

				if ($selection == 'tournament') {
					$ical .= "BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "debalderin.nl
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "
DTSTART:" . date('Ymd', $event_startdate) . "
DTEND:" . date('Ymd', $event_enddate) . "
SUMMARY:" . $event['event'] . "
LOCATION:" . $event['location'] . "
END:VEVENT\n";					
				} else {
				$ical .= "BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "debalderin.nl
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "
DTSTART:" . date('Ymd', $event_startdate) . "T" . date('Gis', $event_time) . "
DTEND:" . date('Ymd', $event_enddate) . "T" . date('Gis', strtotime('+2 hours', $event_time)) . "
SUMMARY:" . $event['event'] . "
LOCATION:" . $event['location'] . "
END:VEVENT\n";
				}
			}

			$ical .= "END:VCALENDAR";

			//set correct content-type-header
			header('Content-type: text/calendar; charset=utf-8');
			header('Content-Disposition: inline; filename="agenda.ics"');
			echo $ical;
		} else {
			?>
				<script type='text/javascript'>
					history.go(-1);
				</script>
			<?php
		}
	} else {
		?>
			<script type='text/javascript'>
				history.go(-1);
			</script>
		<?php
	}
?>
