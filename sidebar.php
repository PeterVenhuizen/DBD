            <section id="sidebar">
				<!-- Agenda -->
				<aside id="agenda_side">
					<header>
						<a href='Agenda/' class='agenda_lnk'>
							<div id='today'>
								<span><?php echo date('j'); ?></span>
							</div>
							<h2>Agenda</h2>
						</a>
					</header>
					<table>

	<?php
		$agenda = mysql_query("SELECT * FROM agenda WHERE start_date BETWEEN CURRENT_DATE() AND CURRENT_DATE()+INTERVAL 2 WEEK AND type != 'tournament' UNION SELECT id, match_date AS start_date, NULL as end_date, match_time AS time, CASE home WHEN 1 THEN CONCAT(team_name, ' - ', opponent) WHEN 0 THEN CONCAT(opponent, ' - ', team_name) END AS event, location, 'match' AS type FROM teams, matches WHERE teams.team_id = matches.team_id AND match_date BETWEEN CURRENT_DATE() AND CURRENT_DATE()+INTERVAL 2 WEEK ORDER BY start_date limit 5");
		$nEvents = mysql_num_rows($agenda);

		if ($nEvents > 0) {
			while ($event = mysql_fetch_array($agenda)) {
				$event_date = strtotime($event['start_date']);
				$event_time = strtotime($event['time']);
				echo '	<tr>
							<td>
								<div class="date">
									<span class="day">' . date('j', $event_date) . '</span>
									<span class="month">' . strtoupper(date('M', $event_date)) . '</span>
								</div>
							</td>
							<td class="time">'; if ($event['time'] != '') { echo date('G:i', $event_time); } echo '</td>
							<td class="event">' . $event['event'] . '</td>
						</tr>';
			}
		} else {
			echo '		<tr>
							<td colspan="3" id="no_events">Geen activiteiten bekend voor de komende twee weken.</td>
						</tr>';
		}
	?>

					</table>
					<footer>
						<a href="Agenda/">Volledige agenda</a>
					</footer>
				</aside>             
                
                <aside id="next_tournament">
                    <header>
                        <img src="assets/img/tournament_icon.png" >
                        <h2>Volgend Toernooi</h2>
                    </header>
                <?php
                    try {
                        $stmt = $db->prepare("SELECT * FROM agenda WHERE end_date > CURRENT_DATE() AND type = 'tournament' ORDER BY start_date ASC LIMIT 1"); 
                        $stmt->execute();
                    } catch(PDOException $ex) { die(); }
                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch();
                        echo '  <h3>' . $row['event'] . '</h3>
                                <b>' . date('d', strtotime($row['start_date'])) . '-' . date('d M', strtotime($row['end_date'])) . ' / ' . $row['location'] . '</b>';
                    } else {
                        echo '  <b>Nog niet bekend.</b>';
                    }
                ?>
                    <footer>
                        <a href="Toernooien/Toernooikalender">Toernooikalender</a>
                    </footer>
                </aside> 
                
                <aside id="birthdays">
                    <header>
                        <img src="assets/img/birthday_icon.png" >
                        <h2>Verjaardagen</h2>
                    </header>
                <?php
                    if(!empty($_SESSION['user'])) {	
                        try {
                            $stmt = $db->prepare("SELECT first_name, prefix, last_name, birth_date FROM users WHERE CONCAT_WS('-', EXTRACT(YEAR FROM CURDATE()), EXTRACT(MONTH FROM birth_date), EXTRACT(DAY FROM birth_date)) BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 WEEK ORDER BY EXTRACT(MONTH FROM birth_date), EXTRACT(DAY FROM birth_date)");
                            $stmt->execute();
                        } catch(PDOException $ex) { die(); }
                        if ($stmt->rowCount() > 0) {
                            foreach ($stmt as $row) {
                                echo '  <div class="peep">  
                                            <div class="date">
                                                <span class="day">' . date('j', strtotime($row['birth_date'])) . '</span>
                                                <span class="month">' . strtoupper(date('M', strtotime($row['birth_date']))) . '</span>
                                            </div>
                                            <b class="birthday_peep">' . $row['first_name'] . ' ' . $row['prefix'] . ' ' . $row['last_name'] . '</b>
                                        </div>';
                            }
                        } else {
                            echo '  <b>Geen verjaardagen in de komende week.</b>';   
                        }
			         } else { echo '	<div class="peep"><a href="login.php">Login</a> om verjaardagen te bekijken.</div>'; }
                ?>
                </aside>
            </section>