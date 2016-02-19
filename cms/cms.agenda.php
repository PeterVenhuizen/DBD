<!DOCTYPE html>

<?php 
	require_once('../assets/config.php'); 
	include 'actions/functions.php';
	ini_set('display_errors', 1);error_reporting(E_ALL);
	date_default_timezone_set('Europe/Amsterdam');
?>

<html>

	<head>
		<meta charset='UTF-8'>
		<title>CMS - Agenda</title>
		<link rel='stylesheet' type='text/css' href='../assets/css/cms_responsive.css'>
		<link rel='icon' href='img/logo_small.png'>
		<!--[if IE]><link rel="shortcut icon" href="img/logo_small.ico"><![endif]-->
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script type='text/javascript' src='js/general.js'></script>
        <script type='text/javascript' src='js/agenda.js'></script>
        <script type='text/javascript' src='js/form_validation.js'></script>
		<meta name="viewport" content="initial-scale=1">
	</head>
    
    <body>
        
        <?php 
        	include('cms.menu.php'); 
        	
        	if (empty($_SESSION['user'])) {
        		header("Location: ../cms/cms.login.php");
        		die();
        	}
        	
        	if ($_SESSION['user']['rights'] < 1) {
        		echo 'Je beschikt niet over de vereiste gebruikersrechten om deze pagina te zien! Voor vragen neem contact op met de <a href="www.debalderin.wur.nl/Contact/">Admin</a>';
        	} else {
        ?>
        
        <main>
        
            <img class='img_get_help' src='../assets/img/whats_this.PNG' alt='Help'>
            <button id='btn_add'>Nieuwe activiteit</button>
            
            <article class='help'>
                <header>
                    <h2>Agenda uitleg</h2>
                </header>
                <p>Voeg activiteiten toe aan de agenda, deze verschijnen in de sidebar op de homepage. Er zijn drie verschillende soorten activiteiten: trainingen, toernooien en diverse. Wedstrijden worden automatisch in de agenda geplaatst, zodra deze bij <a href='cms.competition.php'>Competitie</a> zijn ingevuld.</p>
            </article>
            
            <form action='' method='POST' id='form_add_event'>
                <h2>Nieuwe activiteit</h2>
                
                <label for='activity'>Activiteit:</label>
                <input type='text' name='activity' id='activity'>
                
                <select name='category' id='category'>
                    <option value='practice'>Training</option>
                    <option value='tournament'>Toernooi</option>
                    <option value='misc'>Diverse</option>
                </select>
                
                <br>
                
                <label for='location'>Locatie:</label>
                <input type='text' name='location' id='location'>
                
                <br>
                
                <label for='start_date'>Start datum:</label>
                <input type='text' name='start_date' id='start_date' placeholder='dd-mm-jjjj'>
                
                <br>
                
                <label for='end_date'>Eind datum:</label>
                <input type='text' name='end_date' id='end_date' placeholder='dd-mm-jjjj'>
                <input type='checkbox' id='use_end_date' name='use_end_date'>
                
                <br>
                
                <label for='time'>Tijd:</label>
                <input type='time' name='time' id='time' maxlength='5' placeholder='18:00'>
                <input type='checkbox' id='use_start_time' name='use_start_time'>
                
                <br><br>
                
                <input type='submit' name='submit_event' id='submit_event' class='submit_form' value='Voeg toe!'>
                
            </form>
            
            <?php
            
                if (isset($_POST['submit_event'])) {
                    $start_date = $_POST['start_date'];
                    $mysql_start_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $start_date)));
                    
                    if (isset($_POST['use_end_date'])) {
                        $end_date = $_POST['end_date'];
                        $mysql_end_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $end_date)));
                    } else { $mysql_end_date = NULL; }
                    
                    $time = (isset($_POST['use_start_time']) ? $_POST['time'] : NULL);
                    
                    try {
                        $stmt = $db->prepare('INSERT INTO agenda (start_date, end_date, time, event, location, type) VALUES (:start_date, :end_date, :time, :event, :location, :type)');
                        $stmt->execute(array(':start_date' => $mysql_start_date, ':end_date' => $mysql_end_date, ':time' => $time, ':event' => $_POST['activity'], ':location' => $_POST['location'], ':type' => $_POST['category'])); 
                    } catch (PDOException $ex) { die(); }
					
					// Save to log
					add_to_log($db, array('user' => $_SESSION['user']['email'], 'action' => 'ADD', 'page' => 'cms.agenda.php', 'desc' => $_POST['activity']));
                    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.agenda.php">';
                }

            ?>
            
            <table id='agenda'>
                <tr>
                    <th class='event_title'>Activiteit</th>
                    <th class='event_date'>Datum</th>
                    <th class='event_time'>Tijd</th>
                    <th class='event_location'>Locatie</th>
                    <th></th>
                </tr>
                <?php
                	try {
                		$stmt = $db->prepare("SELECT * FROM agenda WHERE CURRENT_DATE() BETWEEN start_date AND end_date OR CURRENT_DATE() <= start_date ORDER BY start_date");
                		$stmt->execute();
                		if ($stmt->rowCount() > 0) {
                			foreach ($stmt as $event) {
				                $event_start_date = strtotime($event['start_date']);
				                $event_end_date = ($event['end_date'] != '' ? strtotime($event['end_date']) : '');
				                $event_time = strtotime($event['time']);
				                
				                echo '  <tr>
				                            <td class="event_title">' . $event['event'] . '</td>
				                            <td class="event_date">' . date('j M', $event_start_date) . ($event_end_date != '' ? ' - ' . date('j M', $event_end_date) : '') . '</td>
				                            <td class="event_time">' . ($event_time != '' ? date('G:i', $event_time) : '') . '</td>
				                            <td class="event_location">' . $event['location'] . '</td>
				                            <td class="event_delete">
				                                <form action="" method="POST" class="delete">
				                                    <button type="submit" name="delete_event" class="btn_delete" value="' . $event['id'] . '" onclick="return confirm(\'Weet je zeker dat je dit event wilt verwijderen?\')"></button>
				                                </form>
				                            </td>
				                        </tr>';
                			}
                		}
                	} catch (PDOException $ex) { }
                ?>
            </table>
            
            <?php
				// Delete agenda event
				if (isset($_POST['delete_event'])) {
	                try {
	                    $stmt = $db->prepare('DELETE FROM agenda WHERE id = :id');
	                    $stmt->execute(array(':id' => $_POST['delete_event']));
	                } catch (PDOException $ex) { die(); }
					
					// Save to log
					add_to_log($db, array('user' => $_SESSION['user']['email'], 'action' => 'ADD', 'page' => 'cms.agenda.php', 'desc' => $_POST['delete_event']));
                    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.agenda.php">';
				}            
            ?>
            
        </main>
        
        <?php 
        	}
        	include('cms.footer.php'); 
        ?>
        
    </body>
    
</html>
