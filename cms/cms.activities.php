<!DOCTYPE html>

<?php require_once('../assets/config.php'); ?>

<html>

	<head>
		<meta charset='UTF-8'>
		<title>CMS - Activiteiten</title>
		<link rel='stylesheet' type='text/css' href='../assets/css/cms_responsive.css'>
		<link rel='icon' href='img/logo_small.png'>
		<!--[if IE]><link rel="shortcut icon" href="img/logo_small.ico"><![endif]-->
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script type='text/javascript' src='js/general.js'></script>
        <script type='text/javascript' src='js/activities.js'></script>
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
                    <h2>Activiteiten uitleg</h2>
                </header>
                <p>Manage activiteiten voor Debbie's Hangout. Geregistreerde leden kunnen zich via Debbie's Hangout aanmelden. Geef iedere activiteit een (unieke) naam, datum en beschrijving en bepaal vervolgens of de activiteit open is voor inschrijving (is online ja of nee). Maak gebruik van de <a href="http://www.w3schools.com/tags/tag_br.asp" target="blank">br-tag</a> voor het invoeren van witregels.</p>
            </article>
            
            <form action='' method='POST' id='form_add_act'>
                <h2>Nieuwe activiteit</h2>
                
                <label for='activity'>Activiteit:</label>
                <input type='text' name='activity' id='activity'>
                
                <br>
                
                <label for='date'>Datum:</label>
                <input type='text' name='date' id='date' placeholder='dd-mm-jjjj'>
                
                <br>
                
                <label for='description'>Beschrijving:</label>
                <textarea name='description' id='description'></textarea>
                
                <br>
                
                <label for='online'>Is online:</label>
                <label class='radio_label'><input type='radio' name='online' value='1' CHECKED>Ja</label>
                <label class='radio_label'><input type='radio' name='online' value='0'>Nee</label>
                
                <br><br>
                
                <input type='submit' name='submit_activity' id='submit_activity' class='submit_form' value='Voeg toe!'>
                
            </form>
            
            <?php
            
            	// Add activity
                if (isset($_POST['submit_activity'])) {
                    $activity = mysql_real_escape_string($_POST['activity']);
                    $date = mysql_real_escape_string($_POST['date']);
                    $mysql_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $date)));
                    $desc = mysql_real_escape_string($_POST['description']);
                    $online = mysql_real_escape_string($_POST['online']);
                    
                    $query = 'INSERT INTO events (title, edate, description, active) VALUES (:title, :date, :desc, :online)';
                    $query_params = array(':title' => $activity, ':date' => $mysql_date, ':desc' => $desc, ':online' => $online);
                    try {
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                        echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.activities.php">';
					} catch (PDOException $ex) { die(); }
                }
                
                // Edit activity
                if (isset($_GET['act_id'])) {
                	try {
                		$stmt = $db->prepare('SELECT * FROM events WHERE id = :id LIMIT 1');
                		$stmt->execute(array(':id' => mysql_real_escape_string($_GET['act_id'])));
                	} catch (PDOException $ex) { die(); }
					if ($stmt->rowCount()) {
	                	$row = $stmt->fetch();
    	            	echo '	<form action="" method="POST" id="form_edit_act" class="edit">
    	            				<h2>Wijzig activiteit</h2>
    	            				
    	            				<label for="edit_activity">Activiteit:</label>
    	            				<input type="text" name="edit_activity" id="edit_activity" value="' . $row['title'] . '">
    	            				
    	            				<br>
    	            				
    	            				<label for="edit_date">Datum:</label>
    	            				<input type="text" name="edit_date" id="edit_date" placeholder="dd-mm-jjjj" value="' . date('d-m-Y', strtotime($row['edate'])) . '">
    	            				
    	            				<br>
    	            				
    	            				<label for="edit_description">Beschrijving:</label>
    	            				<textarea name="edit_description" id="edit_description">' . $row['description'] . '</textarea>
    	            				
    	            				<br>
    	            				
    	            				<label for="edit_online">Is online:</label>
    	            				<input type="radio" name="edit_online" value="1"' . ($row['active'] ? 'CHECKED' : '') . '>Ja
    	            				<input type="radio" name="edit_online" value="0"' . (!$row['active'] ? 'CHECKED' : '') . '>Nee
    	            				
    	            				<br><br>
    	            				
    	            				<input type="submit" name="submit_edit_activity" id="submit_edit_activity" class="submit_form" value="Wijzig!">
    	            			</form>';
    	            }
    	            
		            if (isset($_POST['submit_edit_activity'])) {
		                $activity = mysql_real_escape_string($_POST['edit_activity']);
		                $date = mysql_real_escape_string($_POST['edit_date']);
		                $mysql_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $date)));
		                $desc = mysql_real_escape_string($_POST['edit_description']);
		                $online = mysql_real_escape_string($_POST['edit_online']);
		                
		                $query = 'UPDATE events SET title = :title, edate = :date, description = :desc, active = :online WHERE id = :id';
		                $query_params = array(':title' => $activity, ':date' => $mysql_date, ':desc' => $desc, ':online' => $online, ':id' => $row['id']);
		                try {
		                    $stmt = $db->prepare($query);
		                    $stmt->execute($query_params);
		                    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.activities.php">';
		                } catch (PDOException $ex) { die(); }
		            }
                }

            ?>
            
            <table id='table_activities'>
                <tr>
                    <th class='act_title'>Activiteit</th>
                    <th class='act_date'>Datum</th>
                    <th class='act_desc'>Beschrijving</th>
                    <th class='act_subscribers'>Aanwezigen</th>
                    <th class='act_online'>Online</th>
                    <th class='act_edit'></th>
                    <th class='act_delete'></th>
                </tr>
                <?php
                    $act_query = mysql_query('SELECT * FROM events WHERE CURRENT_DATE() <= edate ORDER BY edate');
                    while ($act = mysql_fetch_assoc($act_query)) {
                        
                        // Get subscribers
                        $subscribers = array();
                        if ($act['subscribers'] != '') {
                            $sub_ids = explode(';', $act['subscribers']);
                            foreach($sub_ids as &$id) {
                                $name_query = mysql_query("SELECT first_name, prefix, last_name FROM users WHERE id = '$id'");
                                while ($row = mysql_fetch_assoc($name_query)) {
                                    array_push($subscribers, $row['first_name'] . ' ' . $row['prefix'] . ' ' . $row['last_name']);   
                                }
                            }
                        }
                        
                        echo '  <tr>
                                    <td class="act_title">' . $act['title'] . '</td>
                                    <td class="act_date">' . date('j M', strtotime($act['edate'])) . '</td>
                                    <td class="act_desc">' . $act['description'] . '</td>
                                    <td class="act_subscribers">' . implode(', ', $subscribers) . '</td>
                                    <td class="act_online">' . ($act['active'] ? 'Ja' : 'Nee') . '</td>
                                    <td class="act_edit"><a href="cms.activities.php?act_id=' . $act['id'] . '" class="btn_edit"></a></td>
                                    <td class="act_delete">
                                        <form action="" method="POST" class="delete">
                                            <button type="submit" name="delete_act" class="btn_delete" value="' . $act['id'] . '" onclick="return confirm(\'Weet je zeker dat je deze activiteit wilt verwijderen?\')"></button>
                                        </form>
                                    </td>
                                </tr>';
                                    
                    }
                ?>
            </table>
            
            <?php
				// Delete agenda event
				if (isset($_POST['delete_act'])) {
	                try {
	                    $stmt = $db->prepare('DELETE FROM events WHERE id = :id');
	                    $stmt->execute(array(':id' => mysql_real_escape_string($_POST['delete_act'])));
	                    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.activities.php">';
	                } catch (PDOException $ex) { die(); }
					echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=cms.activities.php">';
				}            
            ?>
            
        </main>
        
        <?php 
        	}
        	include('cms.footer.php'); 
        ?>
        
    </body>
    
</html>
