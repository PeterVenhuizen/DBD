<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Login</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">       
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
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
            $submitted_email = '';

            if (isset($_POST['login_submit'])) { 
                
                // Check if email is in users table
                $query = " SELECT * FROM users WHERE email = :email"; 

                $query_params = array(':email' => $_POST['login_email']); 

                try { 
                    $stmt = $db->prepare($query); 
                    $result = $stmt->execute($query_params); 
                } catch(PDOException $ex) { die(); } 

                $login_ok = false; 

                $row = $stmt->fetch();
                if ($row) { 
                    
                    // Check user password
                    $check_password = hash('sha256', $_POST['login_password'] . $row['salt']); 
                    for($round = 0; $round < 65536; $round++) { 
                        $check_password = hash('sha256', $check_password . $row['salt']); 
                    } 

                    if ($check_password === $row['password']) { 
                        $login_ok = true; 
                    } 
                } 
                
                if ($login_ok) { 
                    unset($row['salt']); 
                    unset($row['password']); 

                    $_SESSION['user'] = $row; 
                    //header("Location: http://www.debalderin.wur.nl/Leden/");
                    header("Location: http://localhost/DBD/Leden/");
                    die();
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
			<form id="login_form" action="login.php" method="post"> 
				<h2>Login</h2>
				
				<label for="login_email">Email:</label> 
				<input type="text" name="login_email" id="login_email" value="<?php echo $submitted_email; ?>" /> 

				<br>

				<label for="login_password">Wachtwoord:</label> 
				<input type="password" name="login_password" id="login_password" value="" /> 
				
				<br><br>
				
				<input type="submit" name="login_submit" id="login_submit" value="Inloggen" /> 
                
                <a href="Wachtwoord_vergeten/" id="a_reset_password">Wachtwoord vergeten</a>
                &#x95;
                <a href="Registreren/" id="a_register">Registreren</a>
                
			</form> 
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
