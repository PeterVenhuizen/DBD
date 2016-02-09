<?php 
	require_once('../config.php');

	if (isset($_POST['person_id'])) {
		$id = mysql_real_escape_string($_POST['person_id']);
		$query = mysql_query("SELECT * FROM users WHERE id = '$id' LIMIT 1");
		
		echo '<h2>Persoonsgegevens</h2>';
		
		while ($person = mysql_fetch_array($query)) {
			#Remove password and salt
            unset($person['salt']); 
            unset($person['password']); 
			
			#Delete person information based on privacy settings
			$privacy = explode(';', $person['privacy']);
			foreach($privacy as &$priv) {
				if ($priv == 'address') {
					unset($person['location']);
					unset($person['street']);
					unset($person['house_nr']);
					unset($person['zip_code']);										
				}
				else {
					unset($person[$priv]);
				}
			}
			
            #Get all committees from this person
			$committees = str_replace(';', ', ', $person['committees']);
			
			echo '	<h3>' . $person['first_name'] . ' ' . $person['prefix'] . ' ' . $person['last_name'] . '</h3>';
			if (!empty($person['birth_date'])) { echo '<h5>' . date('d M', strtotime($person['birth_date'])) . '</h5><br><br>'; }
			if (!empty($person['street'])) { echo '<label>Adres: </label><h5>' . $person['street'] . ' ' . $person['house_number'] . ', ' . $person['zip_code'] . '</h5><br>'; }
			if (!empty($person['location'])) { echo '<label>Plaats: </label><h5>' . $person['location'] . '</h5><br>'; }			
			if (!empty($person['email'])) { echo '<label>Email: </label><h5>' . $person['email'] . '</h5><br>'; }
			if (!empty($person['tel_nr'])) { echo '<label>Telefoon nummer: </label><h5>' . $person['tel_nr'] . '</h5><br><br>'; }
			echo '	<label class="lbl_committees">Commissies:</label><br><h5>' . $committees . '</h5>';
		} 
	}
?>