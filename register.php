<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Register</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">       
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
        <script type="text/javascript" src="assets/js/validation.js"></script>
        <script type="text/javascript" src="assets/js/register.js"></script>
        <!--[if IE]>
        <script>
            $(document).ready(function () {
                document.createElement('main');
            });
        </script>
        <![endif]-->          
		<meta name="viewport" content="initial-scale=1">
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
        
        <?php
            if (isset($_POST['register_submit'])) {       

                // INSERT query with parameters to prevent SQL injection
                $query = "INSERT INTO users (password, salt, email) VALUES (:password, :salt, :email)"; 

                // A salt is randomly generated here to protect again brute force attacks and rainbow table attacks.  
                $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

                // This hashes the password with the salt so that it can be stored securely in your database.
                $password = hash('sha256', $_POST['register_password'] . $salt); 

                for($round = 0; $round < 65536; $round++) { 
                    $password = hash('sha256', $password . $salt); 
                } 

                $query_params = array(':password' => $password, ':salt' => $salt, ':email' => $_POST['register_email']); 

                try { 
                    // Execute the query to create the user 
                    $stmt = $db->prepare($query); 
                    $result = $stmt->execute($query_params); 
                } catch(PDOException $ex) { die(); } 

                // This redirects the user back to the login page after they register 
                header("Location: Registreren/succes/"); 
                die();
            }     
        ?>
            
			<!-- Register form -->
			<form id="register_form" action="register.php" method="post"> 
				<h2>Registeer</h2> 
				
				<label for="register_email">Email:</label>
				<input type="text" name="register_email" id="register_email" /> 
				
				<br>
				
				<label for="register_password">Wachtwoord:</label>
				<input type="password" name="register_password" id="register_password" /> 
				
				<br>
				
				<label for="register_confirm_password">Herhaal wachtwoord:</label>
				<input type="password" name="register_confirm_password" id="register_confirm_password" /> 
				
				<br>
				<br>
				
				<label for="register_activation_code">Activatie code:</label>
				<input type="text" name="register_activation_code" id="register_activation_code" />

<?php
	if (isset($_GET['succes'])) {
		echo '	<div id="register_success">Je hebt jezelf succesvol geregistreerd! Je kan nu <a href="Login/">inloggen</a> met je email en wachtwoord.</div>';
	}
?>					
				<div id="register_error"></div>
				
				<br>				

				<button id="btn_check_code">Controleer code</button>
				
				<input type="submit" name="register_submit" id="register_submit" value="Registeer" /> 
			</form>            
            
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
