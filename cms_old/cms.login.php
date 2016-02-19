<?php require_once('../assets/config.php'); ?>

<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Login</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
		<script type='text/javascript'>
			$(document).ready(function() {
				
				//Hide error div
				$('#login_error').hide();
				
				// Check if field is empty
				jQuery.fn.extend({
					is_not_empty: function() {
						var text = $(this).val();
						if (text.length == 0) {
							$(this).removeClass('correct');
							$(this).addClass('error');
							return false;
						} else {
							$(this).removeClass('error');
							$(this).addClass('correct');
							return true;
						}
					}
				})	
				
				function isValidEmailAddress(emailAddress) {
					var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
					return pattern.test(emailAddress);
				};
				
				/* LOGIN FORM VALIDATION */
				//Check if a email is filled in
				$('#login_email').focusout(function() {
					var email = $(this).val();
					if (!isValidEmailAddress(email)) {
						$('#login_error').show();
						$('#login_error').html('Vul een geldig email-adres in.');						
					} else {
						$('#login_error').hide();					
					}
				});
				// Check if a password is filled in
				$('#login_password').focusout(function() {
					if (!$(this).is_not_empty()) {
						$('#login_error').show();
						$('#login_error').html('Vul een wachtwoord in.');					
					} else {
						$('#login_error').hide();						
					}
				});
				
			});
		</script>
	</head>
	
	<body>

<?php 
 
 	$submitted_email = '';
 
    if (isset($_POST['login_submit'])) { 
        $query = " SELECT * FROM users WHERE email = :email"; 
         
        $query_params = array(':email' => $_POST['login_email']); 
         
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } catch(PDOException $ex) { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 

        $login_ok = false; 

        $row = $stmt->fetch(); 
        if ($row) { 
            $check_password = hash('sha256', $_POST['login_password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++) { 
                $check_password = hash('sha256', $check_password . $row['salt']); 
            } 
             
            if ($check_password === $row['password'] AND $row['rights'] > 0) { 
                $login_ok = true; 
            } 
        } 

        if ($login_ok) { 
            unset($row['salt']); 
            unset($row['password']); 

            $_SESSION['user'] = $row; 
            header("Location: index.php"); 
            die("Redirecting to: index.php");             

        } else { 
?>
	<script>
		$(document).ready(function() {
			alert('Inloggen mislukt, probeer opnieuw.');
		});
	</script>
<?php

        } 
    }  
    
?> 
		<!-- Login form -->
		<form id="form_login" action="cms.login.php" method="post"> 
			<h1>Login</h1> 
			
			<label for="login_email">Email:</label> 
			<input type="text" name="login_email" id="login_email" value="<?php echo $submitted_email; ?>" /> 

			<br />

			<label for="login_password">Wachtwoord:</label> 
			<input type="password" name="login_password" id="login_password" value="" /> 
			
			<div id="login_error"></div>
			
			<br />
			
			<input type="submit" name="login_submit" id="login_submit" value="Login" /> 
		</form> 		
	</body>
</html>