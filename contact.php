<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Contact</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">       
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>
        <script type="text/javascript" src="assets/js/validation.js"></script>
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
        <?php require 'PHPMailer/PHPMailerAutoload.php'; ?>
        <?php include('header.php'); ?>
		
		<main>
            
            <form id="contact_form" method="post" action="">
                <h2>Contactformulier</h2>
                
                <div id="first_name">
                    If you see this, leave this field blank!
                    <input type="text" name="first_name" class="first_name" value="" />
                </div>
                
                <label for="contact_name">Naam: <span class="req">*</span></label>
                <input type="text" name="contact_name" id="contact_name">
                
                <label for="contact_email">Email: <span class="req">*</span></label>
                <input type="text" name="contact_email" id="contact_email">
                
                <label for="contact_subject">Onderwerp: <span class="req">*</span></label>
                <input type="text" name="contact_subject" id="contact_subject">
                
                <label for="contact_recipient">Aan: </label>
                <select name="contact_recipient" id="contact_recipient">
                <?php
                    try {
                        $stmt = $db->prepare("SELECT * FROM contacts");
                        $stmt->execute();
                    } catch (PDOException $ex) { die("Failed to run query: " . $ex->getMessage()); }
                    if ($stmt->rowCount() > 0) {
                        foreach ($stmt as $contact) {
                            echo '<option value="' . $contact['email'] . '###' . $contact['function'] . '">' . $contact['function'] . '</option>';    
                        }
                    }
                ?>
                </select>
                
                <label for="contact_message">Bericht: <span class="req">*</span></label>
                <textarea name="contact_message" id="contact_message"></textarea>
                
                <input type="submit" name="contact_submit" id="contact_submit" value="Verzenden">
                    
            </form>
            
            <?php
                
                // Form validation and submit 
                if (isset($_POST['contact_submit'])) {
                    
                    // Check if the honeypot is empty
                    if (empty($_POST['first_name'])) {
                        
                        // Collect the variables
						$name = mysql_real_escape_string($_POST['contact_name']);
						$email = mysql_real_escape_string($_POST['contact_email']);
						$subject = mysql_real_escape_string($_POST['contact_subject']);
						
                        // Get the recipient email and function
						$to_array = explode('###', mysql_real_escape_string($_POST['contact_recipient']));
						$recipient = $to_array[0];
						$commission = $to_array[1];
                        
                        // Get and build HTML message
						$message = mysql_real_escape_string($_POST['contact_message']);
                        
                        $html_message = '
							<html>
								<head>
									<title>' . $subject . '</title>
								</head>
								<body>
									<h2>Message send from www.debalderin.wur.nl at ' . date('l d F') . ' at ' . date('G:i') . '</h2>
									<h3>Send by <b>' . $name . '</b> (' . $email . ') to the <b>' . $commission . '</b></h3>						
									<p>' . $message . '</p>
								</body>
							</html>
						';
						
                        // Add headers
						$headers = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
						$headers .= 'To: ' . $commission . ' <' . $recipient . '>' . "\r\n";
						$headers .= 'From: www.debalderin.wur.nl' . "\r\n";
                        
                        mail($recipient, $subject, $html_message, $headers);
                        
			?>
				<script>alert('Uw email is succesvol verzonden.');</script>
			<?php
                        
                    } else {
                        // There was a bot or someone filled 
                        // the honeypot field
                    }
                }

            ?>
            
            
            <article class="contact">
                <header><h2>Postadres</h2></header>
                <p>W.S.K.V. Débaldérin<br>p/a Bornsesteeg 2<br>6708 PE Wageningen</p>
            </article>
            
            <article class="contact">
                <header><h2>Terrein</h2></header>
                <p>Sports Centre de Bongerd<br>Bornsesteeg 2<br>6708 PE Wageningen</p>
            </article>
            
            <?php
                try {
                    $stmt = $db->prepare("SELECT first_name, prefix, last_name, tel_nr FROM users WHERE committees LIKE '%wedstrijdsecretaris%' LIMIT 1");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch();
                        echo '  <article class="contact">
                                    <header><h2>Wedstrijdsecretaris</h2></header>
                                    <p>' . $row['first_name'] . ' ' . $row['prefix'] . ' ' . $row['last_name'] . '<br>' . $row['tel_nr'] . '</p>
                                </article>';
                    }
                } catch (PDOException $ex) { die(); }
            ?>
            
            <div id="google_maps">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2457.377647382361!2d5.6687175!3d51.9817701!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c7acbd2127eaeb%3A0x60b81be75ecd5653!2sBornsesteeg+2%2C+Wageningen+UR%2C+6708+PE+Wageningen!5e0!3m2!1snl!2snl!4v1408523409515" width="100%" height="450" frameborder="0" style="border:0"></iframe>
            </div>
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
