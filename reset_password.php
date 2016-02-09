<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Wachtwoord vergeten</title>
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
                // PASSWORD FORMS
                if (isset($_GET['email']) AND isset($_GET['key'])) {
                    
                    // New password form
                    echo '		<form id="reset_pw_form" action="" method="POST">

                                    <h2>Nieuw wachtwoord</h2>

                                    <input type="hidden" name="hidden_email" value="' . $_GET['email'] . '" />
                                    <input type="hidden" name="hidden_key" value="' . $_GET['key'] . '" />

                                    <label for="reset_password">Nieuw wachtwoord:</label>
                                    <input type="password" name="reset_password" id="reset_password_field" />

                                    <br>

                                    <label for="reset_password_confirm">Bevestig wachtwoord:</label>
                                    <input type="password" name="reset_password_confirm" id="reset_password_confirm" />

                                    <br>

                                    <input type="submit" name="new_password_submit" id="new_password_submit" value="Verstuur" />

                                </form>';                   
                } else {
                    
                    // User email field
                    echo '  <form id="forgotten_form" action="reset_password.php" method="POST">
                
                                <h2>Wachtwoord vergeten</h2>

                                <label for="user_email">Email: </label>
                                <input type="text" id="user_email" name="user_email">
                                
                                <input type="submit" name="submit_forgotten" id="submit_forgotten" value="Verstuur">

                            </form>'; 
                }
                
                
                // HANDLING FORMS
                // Handle users password forgotten request
                if (isset($_POST['submit_forgotten'])) {
                    
                    // Get user email
                    $email = mysql_real_escape_string($_POST['user_email']);
                    
                    // Check if email exists in users
                    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
                    $stmt->execute(array(':email' => $email));
                    if ($stmt->rowCount() > 0) {
                        
                        // Generate a key
                        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
                        $activation_key = '';
                        for ($i = 0; $i < 32; $i++) {
                            $activation_key .= $characters[rand(0, strlen($characters)-1)];
                        }
                        
                        // Fetch user id
                        $row = $stmt->fetch();
                        $user_id = $row['id'];
                        
                        // Add record in forgetfull users table
                        $stmt = $db->prepare("INSERT INTO forgetful_users VALUES (:reset_key, :email, :id)");
                        try {
                            $stmt->execute(array(':reset_key' => $activation_key, ':email' => $email, ':id' => $user_id));

                            // Send reset password email
                            $name = $email;
                            $to = $email;
                            $link = $config['absolute_path'] . 'reset_password.php?email=' . $to . '&key=' . $activation_key;
                            
                            $html_message = '   Zeer gewaardeerd Débaldérin-lid, 
                                                <br><br>
                                                Met behulp van deze email is het mogelijk om opnieuw toegang te krijgen tot de Débaldérin website. 
                                                Volg deze <a href="' . $link . '">link</a> en maak een nieuw wachtwoord aan.
                                                Indien de link niet werkt, kopieer dan deze url => ' . $link . ' <= naar jouw favoriete browser.
                                                <br><br>
                                                Met vriendelijke groet,
                                                <br><br>
                                                Peter Venhuizen
                                                <br>
                                                Admin www.debalderin.wur.nl';

                            $headers = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

                            // Additional headers
                            $headers .= 'To: ' . $name . ' <' . $to . '>' . "\r\n";
                            $headers .= 'From: www.debalderin.wur.nl' . "\r\n";

                            mail($to, 'Débaldérin - Wachtwoord vergeten', $html_message, $headers);

                            // Redirect the user back to the reset password page
                            header("Location: Wachtwoord_vergeten/reset/");
                            
                        } catch(PDOException $ex) { die(); }    
                        
                    }
                
                // Handle new password
                } else if (isset($_POST['new_password_submit'])) {
                    
                    // Get variables
                    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
                    $email = $_POST['hidden_email'];
                    $key = $_POST['hidden_key'];

                    // Password hashing
                    $password = hash('sha256', $_POST['reset_password'] . $salt);
                    for($round = 0; $round < 65536; $round++) { 
                        $password = hash('sha256', $password . $salt); 
                    }
                    
                    // Check email and key in tabel
                    $stmt = $db->prepare("SELECT * FROM forgetful_users WHERE email = :email AND reset_key = :key");
                    $stmt->execute(array(':email' => $email, ':key' => $key));
                    if ($stmt->rowCount() > 0) {
                        
                        // Get user id
                        $row = $stmt->fetch();
                        $user_id = $row['user_id'];
                        
                        // Update user password
                        try {
                            $stmt = $db->prepare("UPDATE users SET salt = :salt, password = :password WHERE id = :user_id");
                            $stmt->execute(array(':salt' => $salt, ':password' => $password, ':user_id' => $user_id));
                            
                            // Remove record from forgetful_users table
                            mysql_query("DELETE FROM forgetful_users WHERE user_id = '$user_id'");
                            
                            // This redirects the user back to the login page after they register 
                            header("Location: reset_password.php?success");
                            die();	
                            
                        } catch(PDOException $ex) { die(); }
                        
                    }
                }

                // MESSAGES
                // Step 1 - Email with instructions
                if (isset($_GET['reset'])) {
                    echo '<p class="pw_message"><b>Stap 1 voltooid!</b><br>Momenteel wordt er een email met verdere instructies aan jou verzonden. Volg de link in deze email om een nieuw wachtwoord aan te maken.</p>';
                }

                // Step 2 - New password is succesfully generated
                if (isset($_GET['success'])) {
                    echo '<p class="pw_message"><b>Stap 2 voltooid!</b><br>Je wachtwoord is gewijzigd! Je kan nu <a href="login.php">inloggen</a> met behulp van je nieuwe wachtwoord.</p>';
                }

            ?>
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
