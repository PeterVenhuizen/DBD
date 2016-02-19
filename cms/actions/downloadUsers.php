<?php
	require_once('../../assets/config.php');
	
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=DBD_ledenlijst_" . date('d-M-Y') . ".txt");
	
	try {
		$stmt = $db->prepare("SELECT first_name, prefix, last_name, gender, birth_date, location, street, house_number, zip_code, tel_nr, committees FROM users ORDER BY last_name");
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach ($stmt as $row) {
				echo $row['first_name'] . "\t" . $row['prefix'] . "\t" . $row['last_name'] . "\t" . $row['gender'] . "\t" . $row['birth_date'] . "\t" . $row['location'] . "\t" . $row['street'] . "\t" . $row['house_number'] . "\t" . $row['zip_cide'] . "\t" . $row['committees'] . "\n";
			}
		}
	} catch (PDOException $ex) { }
?>
