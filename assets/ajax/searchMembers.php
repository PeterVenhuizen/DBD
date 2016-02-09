<?php 
	require_once('../config.php');

	if (isset($_POST['search_data'])) {
		$name = mysql_real_escape_string($_POST['search_data']);
		$query = mysql_query("SELECT id, first_name, prefix, last_name, committees FROM users WHERE CONCAT_WS(' ', first_name, prefix, last_name) LIKE '%$name%' OR CONCAT_WS(' ', first_name, last_name) LIKE '%$name%' OR committees LIKE '%$name%'");
		#$query = mysql_query("SELECT id, first_name, prefix, last_name, committees FROM users WHERE first_name LIKE '%$name%' OR prefix LIKE '%$name%' OR last_name LIKE '%$name%' OR committees LIKE '%$name%'");
		
		echo '<h2>Resultaat</h2>';
		
		if (mysql_num_rows($query) >= 1) {
			while ($person = mysql_fetch_array($query)) {
				$committees = str_replace(';', ', ', $person['committees']);
				echo '	<div class="' . $person['id'] . ' search_person_info">
							<h3 class="person_name">' . $person['first_name'] . ' ' . $person['prefix'] . ' ' . $person['last_name'] . '</h3>
							<p class="person_committees"><i class="i_committees">Commissies: </i>' . $committees . '</p>
						</div>';
			} 
		} else {
			echo 'Geen personen gevonden.';
		}
	}
?>