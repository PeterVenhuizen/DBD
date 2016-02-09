<?php 
	require_once('../config.php');

	if (isset($_POST['check_email'])) {
		$mail = mysql_real_escape_string($_POST['check_email']);

		$query = "SELECT * FROM users WHERE email = :email";
		$query_params = array(':email' => $mail);
		
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex)  { die(); }
		
		$row = $stmt->fetch();

		if ($row) {
			echo "Het email-adres (" . $mail . ") is al in gebruik.";
		} else {
			echo "true";
		}

	}
	
	if (isset($_POST['activation_code'])) {
		$activation_code = mysql_real_escape_string($_POST['activation_code']);

        $query = " SELECT * FROM activation"; 
         
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute(); 
        } catch(PDOException $ex) { die(); } 

        $login_ok = false; 

        $row = $stmt->fetch(); 
        if ($row) { 
            $check_code = hash('sha256', $activation_code . $row['salt']); 
            for($round = 0; $round < 65536; $round++) { 
                $check_code = hash('sha256', $check_code . $row['salt']); 
            } 
             
            if ($check_code === $row['code']) { 
                echo "true"; 
            } else {
            	echo "De activatie code is incorrect.";
            }
        } 
	}	
?>
