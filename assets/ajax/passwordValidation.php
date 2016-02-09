<?php 
	require_once('../config.php');

	if (isset($_POST['check_password'])) {
		$password = mysql_real_escape_string($_POST['check_password']);
		$id = mysql_real_escape_string($_POST['user_id']);

		$query = "SELECT * FROM users WHERE id = :id";
		$query_params = array(':id' => $id);
		
		try { 
			$stmt = $db->prepare($query); 
			$result = $stmt->execute($query_params); 
		} catch(PDOException $ex) { 
			//Do nothing
		} 

		$login_ok = false; 

		$row = $stmt->fetch();
		if ($row) { 
			$check_password = hash('sha256', $password . $row['salt']); 
			for($round = 0; $round < 65536; $round++) { 
				$check_password = hash('sha256', $check_password . $row['salt']); 
			} 
		 
			if ($check_password === $row['password']) { 
				$login_ok = true; 
			} 
		} 

		if ($login_ok) { 
			echo "true";
		} else {
			echo "Wachtwoord is niet correct, probeer opnieuw.";
		}
	}
?>
