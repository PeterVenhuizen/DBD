<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Agenda</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
        <!--[if IE]>
        <script>
            $(document).ready(function () {
                document.createElement('main');
            });
        </script>
        <![endif]-->         
		<meta name="viewport" content="initial-scale=1">
        <script>
            $(document).ready(function () {
               	$('.ical_btn').click(function() {
                    var selection = $('.select_type').val();
                    window.location.href = 'assets/ajax/ical.php?agenda=' + selection;
                });

                $('.whats_this_btn').click(function() {
                    $('div.calendar_help').toggle();
                });	 
            });
        </script>
	</head>

	<body>
    
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.0";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>         
        
		<?php include_once("analyticstracking.php"); ?>        
        
		<?php include('header.php'); ?>      
        
		<main>
            
            <div id="agendaWrapper">
                <h2>Agenda</h2>
            
                <!-- Main content section  -->
                <table id='agenda'>
                    <tr>
                        <th>Datum</th>
                        <th>Tijd</th>					
                        <th>Activiteit 
                            <select class='select_type'>
                                <option value='*'>Alles</option>
                                <option value='practice'>Training</option>
                                <option value='match'>Wedstrijd</option>
                                <option value='tournament'>Toernooi</option>
                                <option value='misc'>Diverse</option>
                            </select>
                        </th>
                        <th class="location">Locatie</th>
                    </tr>

                <?php
                    if (isset($_GET['type'])) {
                        $type = $_GET['type'];
                        if ($type != 'match' AND $type != '*') {
                            $agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date AND type = '$type' ORDER BY start_date, time");
                        } else if ($type == 'match') {
                            $agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date AND type = '$type' UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND CURRENT_DATE <= match_date ORDER BY start_date, time"); 
                        } else {
                            $agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND CURRENT_DATE <= match_date ORDER BY start_date, time");
                        }
                    } else {
                        $agenda = mysql_query("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND CURRENT_DATE <= match_date ORDER BY start_date, time");
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
                                        <td colspan="4">Geen activiteiten voor deze categorie gevonden.</td>
                                    </tr>';
                    }
                ?>

                </table>
            
                <button class='ical_btn'>Voeg toe aan agenda</button>
                <a class='whats_this_btn'></a>			
                <div class='calendar_help'>
                    Download het .ics bestand met de events uit de agenda. Het .ics bestand wordt herkend door o.a. Windows Outlook en Apple's iCal. <br /> 
                    Android gebruikers kunnen ICS Importer downloaden om het .ics bestand toe te voegen aan hun agenda.
                </div>   
            
            </div>
                
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
