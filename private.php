<?php require_once('assets/config.php'); ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">	
		<title>W.S.K.V. Débaldérin - Debbie's Hangout</title>
        <!--<base href="http://www.debalderin.wur.nl/">-->
        <base href="http://localhost/DBD/">      
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="shortcut icon" href="assets/img/dbd_logo.ico" >
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/general.js"></script>        
        <script type="text/javascript" src="assets/js/hangout.js"></script>
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
        
        <?php include('header.php'); ?>
		      
		<main>
            
            <div id="privateWrapper">
            
                <h2>Debbie's Hangout</h2>

                <?php
                    if (isset($_SESSION['user'])) {

                        // Select all information from this user from the database
                        $query = "SELECT * FROM users WHERE id = :id"; 
                        $query_params = array(':id' => $_SESSION['user']['id']); 
                        try { 
                            $stmt = $db->prepare($query); 
                            $result = $stmt->execute($query_params); 
                        } catch(PDOException $ex) { die(); }
                        $user_info = $stmt->fetch();
                ?>
                
                <section id="search_members">
                    <label for="private_search" id="search_members_title">Zoek leden</label>
                    <input type="text" name="private_search" id="private_search" placeholder="bv. 'Peter' of 'WWW-cie'" autocomplete="off" onclick="searchMembers(this);">
                    <p id="search_explanation">Start met typen om leden te zoeken. Je kan zoeken gebaseerd op naam of commissie.</p>
                    <div id="private_search_result"></div>
                    <div id="selected_person_info"></div>
                </section>  
                
                <section id="activities">
                    <h2>Activiteiten</h2>
                    <?php
                        $nr = $mysqli->query("SELECT count(*) AS nr FROM events WHERE CURRENT_DATE() <= edate AND active ORDER BY edate")->fetch_object()->nr;
                        echo '		<span id="nr_activities">(' . $nr . ')</span>';
                    ?>				
                    <div id="activities_container">
                    <?php

                        $count = 1;
                        $left_column = '<div id="activity_left_column">';
                        $right_column = '<div id="activity_right_column">';

                        $activities = mysql_query("SELECT * FROM events WHERE CURRENT_DATE() <= edate AND active ORDER BY edate");
                        if (mysql_num_rows($activities) > 0) {
                            while ($activity = mysql_fetch_assoc($activities)) {

                                $subscribers = array();
                                $isPresent = false;
                                if ($activity['subscribers'] != '') {
                                    $subscriber_ids = explode(';', $activity['subscribers']);
                                    foreach($subscriber_ids as &$id) {
                                        if ($_SESSION['user']['id'] == $id) { $isPresent = true; }
                                        $query = mysql_query("SELECT first_name, prefix, last_name, email FROM users WHERE id = '$id'");

                                        while ($row = mysql_fetch_assoc($query)) {
                                            if ($row['first_name'] != '') { array_push($subscribers, $row['first_name'] . ' ' . $row['prefix'] . ' ' . $row['last_name']); }
                                            else { array_push($subscribers, $row['email']); }
                                        }
                                    }
                                }

                                $tmp_activity = '	<div class="activity">
                                                        <h2 class="activity_title">' . $activity['title'] . '</h2>
                                                        <br>
                                                        <span class="activity_date">' . date('d-m-Y', strtotime($activity['edate'])) . '</span>
                                                        <div class="activity_body">
                                                            <p class="activity_description">' . $activity['description'] . '</p>

                                                            <h3 class="activity_present">Aanwezigen: </h3>
                                                            <p class="activity_subscribers">' . implode(', ', $subscribers) . '</p>';

                                    if ($isPresent) { $tmp_activity .= '<button class="btn_activity_unsubscribe" id="' . $activity['id'] . '" value="' . $_SESSION['user']['id'] . '">Toch niet... :(</button>'; }
                                    else { $tmp_activity .= '<button class="btn_activity_subscribe" id="' . $activity['id'] . '" value="' . $_SESSION['user']['id'] . '">Ik ben erbij! :)</button>'; }

                                $tmp_activity .= ' 		</div>
                                                    </div>';

                                if ($count % 2 == 0) { $right_column .= $tmp_activity; } 
                                else { $left_column .= $tmp_activity; }

                                $count++;
                            }

                            echo $left_column . '</div>' . $right_column . '</div>';

                        } else {
                            echo 'Er zijn momenteel geen activeiten bekend.';
                        }                        
                    ?>
                    </div>
                </section>	
                
                <section id="member_downloads">
                    <h2>Downloads</h2>
                    <ul id="list_downloads">
    <?php
        $path = 'downloads/';
        $forbidden = array(".", "..", ".DS_Store");

        $files = array();

        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                if (!in_array($extension, array('jpg', 'jpeg', 'png', 'gif', 'zip', 'xls', 'xlsx', 'doc', 'docx', 'pdf', 'mp3'))) { $extension = 'unknown'; }			
                if (!in_array($entry, $forbidden)) {
                    echo '	<li class="download ' . $extension . '"><a href="' . 'downloads/' . $entry . '">' . $entry . '</a></li>';
                } 
            }
        }
        closedir($handle);
    ?>				
                    </ul>
                </section>
                
                <section id="user_info">
                    <h2>Verander account informatie</h2>
                    <form id="form_change_user_info" method="POST">

                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_info['id']; ?>">

                    <label for="user_first_name">Voornaam <span class="req">*</span></label>
                    <input type="text" name="user_first_name" id="user_first_name" value="<?php echo $user_info['first_name']; ?>">

                    <br>

                    <label for="user_prefix">Tussenvoegsel</label>
                    <input type="text" name="user_prefix" id="user_prefix" value="<?php echo $user_info['prefix']; ?>">

                    <br>

                    <label for="user_last_name">Achternaam <span class="req">*</span></label>
                    <input type="text" name="user_last_name" id="user_last_name" value="<?php echo $user_info['last_name']; ?>">

                    <br>

                    <label for="user_gender">Geslacht <span class="req">*</span></label>
                    <input type="radio" name="user_gender" id="user_gender" value="M" <?php if ($user_info['gender'] == 'M') echo 'CHECKED'; ?>> Man
                    <input type="radio" name="user_gender" id="user_gender" value="F" <?php if ($user_info['gender'] == 'F') echo 'CHECKED'; ?>> Vrouw

                    <br>

                    <label for="user_birth_date">Geboorte datum <span class="req">*</span></label>
                    <input type="text" name="user_birth_date" id="user_birth_date" value="<?php if ($user_info['birth_date'] != '') { echo date('d-m-Y', strtotime($user_info['birth_date'])); } ?>" placeholder="31-01-2014">

                    <br>
                    <br>

                    <label for="user_location">Plaats</label>
                    <input type="text" name="user_location" id="user_location" value="<?php echo $user_info['location']; ?>">

                    <br>    
                        
                    <label for="user_street">Straat</label>
                    <input type="text" name="user_street" id="user_street" value="<?php echo $user_info['street']; ?>">

                    <br>

                    <label for="user_house_number">Huisnummer</label>
                    <input type="text" name="user_house_number" id="user_house_number" value="<?php echo $user_info['house_number']; ?>">

                    <br>

                    <label for="user_zip_code">Postcode</label>
                    <input type="text" maxlength="6" name="user_zip_code" id="user_zip_code" value="<?php echo $user_info['zip_code']; ?>" placeholder="1234AB">

                    <br>
                    <br>

                    <label for="user_email">Email <span class="req">*</span></label>
                    <input type="email" name="user_email" id="user_email" value="<?php echo $user_info['email']; ?>">

                    <br>

                    <label for="alt_email">Alternatief email</label>
                    <input type="email" name="alt_email" id="alt_email" value="<?php echo $user_info['alt_email']; ?>">

                    <br>				

                    <label for="user_tel_nr">Telefoon nummer</label>
                    <input type="text" name="user_tel_nr" id="user_tel_nr" value="<?php echo $user_info['tel_nr']; ?>">

                    <br>

                    <label for="user_committees">Commissies</label>
                    <input type="text" name="user_committees" id="user_committees" value="<?php echo $user_info['committees']; ?>" placeholder="bv. bestuur;voorzitter;fluitcie">

                    <br>


                    <label for="user_privacy">Onzichtbaar</label><input type="checkbox" name="user_privacy[]" value="email" <?php if (strpos($user_info['privacy'], 'email') !== false) { echo "CHECKED"; } ?>> Email(s)</input>
                    <br>
                    <label></label><input type="checkbox" name="user_privacy[]" value="tel_nr" <?php if (strpos($user_info['privacy'], 'tel_nr') !== false) { echo "CHECKED"; } ?> > Telefoon nummer</input>
                    <br>
                    <label></label><input type="checkbox" name="user_privacy[]" value="address" <?php if (strpos($user_info['privacy'], 'address') !== false) { echo "CHECKED"; } ?>> Adres</input>

                    <br>

                    <input type="submit" name="submit_user_info_form" id="submit_user_info_form" value="Wijzig" />
    <?php 

    if (isset($_POST['submit_user_info_form'])) {
        $id = mysql_real_escape_string($_POST['user_id']);
        $first_name = mysql_real_escape_string($_POST['user_first_name']);
        $prefix = mysql_real_escape_string($_POST['user_prefix']);
        $last_name = mysql_real_escape_string($_POST['user_last_name']);
        $gender = mysql_real_escape_string($_POST['user_gender']);
        $birth_date = mysql_real_escape_string($_POST['user_birth_date']);
        $mysql_birth_date = date("Y-m-d H:i:s", strtotime($birth_date));
        $location = mysql_real_escape_string($_POST['user_location']);
        $street = mysql_real_escape_string($_POST['user_street']);
        $house_nr = mysql_real_escape_string($_POST['user_house_number']);
        $zip_code = mysql_real_escape_string($_POST['user_zip_code']);
        $email = mysql_real_escape_string($_POST['user_email']);		
        $alt_email = mysql_real_escape_string($_POST['alt_email']);
        $tel_nr = mysql_real_escape_string($_POST['user_tel_nr']);
        $committees = mysql_real_escape_string($_POST['user_committees']);

        $privacy_collect = array();
        if (!empty($_POST['user_privacy'])) {
            foreach($_POST['user_privacy'] as $check) {
                array_push($privacy_collect, $check);
            }
        }
        $privacy = implode(';', $privacy_collect);

        $query = "UPDATE `users` SET email = :email, alt_email = :alt_email, first_name = :first_name, prefix = :prefix, last_name = :last_name, gender = :gender, birth_date = :birth_date, location = :location, street = :street, house_number = :house_nr, zip_code = :zip_code, tel_nr = :tel_nr, committees = :committees, privacy = :privacy WHERE id = :id";
        $query_params = array(':email' => $email, ':alt_email' => $alt_email, ':first_name' => $first_name, ':prefix' => $prefix, ':last_name' => $last_name, ':gender' => $gender, ':birth_date' => $mysql_birth_date, ':location' => $location, ':street' => $street, ':house_nr' => $house_nr, ':zip_code' => $zip_code, ':tel_nr' => $tel_nr, ':committees' => $committees, ':privacy' => $privacy, ':id' => $id);

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($query_params);
            echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=Leden/">';		
        } catch(PDOException $ex)  {
            #Do nothing
            die(); 
        }

    }
    ?>	

                </form>
                    <div id="user_info_explanation">
                        <h2>Info</h2>
                        <p>Verander hier je account informatie, de velden met een * zijn verplicht. Deze informatie helpt de functionaliteit van deze verder pagina verbeteren. Leden kunnen elkaar namelijk zoeken op basis van hun naam en de commissies waarin ze zitten. <br />Verder zal je geboortedatum gebruikt worden 
                        voor het verjaardag overzicht en als je jezelf aanmeld voor een Event op deze pagina, zal je naam bij dat Event worden toegevoegd.</p>
                        <br>
                        <p>Wil je niet dat andere leden jou informatie kunnen zien, of enkel delen hiervan, selecteer dan de opties bij "Onzichtbaar" welke je niet met andere leden wilt delen.</p>
                        <br>
                        <p>Note: <br> Plaats tussen je commissies een ';' om deze te scheiden.</p>
                    </div>
                </section>                
                
                <section id="change_password">
                    <h2>Verander account wachtwoord</h2>
                    <form id="form_change_user_password" method="POST">

                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_info['id']; ?>">				

                        <label for="current_pw">Huidig wachtwoord</label>
                        <input type="password" name="current_pw" id="current_pw" />

                        <br>
                        <br>

                        <label for="new_pw">Nieuw wachtwoord</label>
                        <input type="password" name="new_pw" id="new_pw" />

                        <br>

                        <label for="repeat_pw">Herhaal wachtwoord</label>
                        <input type="password" name="repeat_pw" id="repeat_pw" />

                        <div id="change_pw_error"></div>

                        <br>

                        <input type="submit" name="change_pw_submit" id="change_pw_submit" value="Wijzig" />

    <?php 

    if (isset($_POST['change_pw_submit'])) {
        $id = mysql_real_escape_string($_POST['user_id']);
        $new_pw = mysql_real_escape_string($_POST['new_pw']);

        #A salt is randomly generated here to protect again brute force attacks and rainbow table attacks.  
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

        #This hashes the password with the salt so that it can be stored securely in your database.
        $password = hash('sha256', $new_pw . $salt); 

        for($round = 0; $round < 65536; $round++) { 
            $password = hash('sha256', $password . $salt); 
        } 

        $query = "UPDATE `users` SET password = :password, salt = :salt WHERE id = :id";
        $query_params = array(':password' => $password, ':salt' => $salt, ':id' => $id);

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($query_params);	
            header('Location: http://www.debalderin.wur.nl/Leden/');
        } catch(PDOException $ex)  {
            #Do nothing
            die(); 
        }

    }
    ?>					

                    </form>
                </section>
    
                <?php      
                    } else {
                        echo '  <p>Je moet <a href="Login/">ingelogd</a> zijn om deze pagina te bekijken!</p>';
                    }
                ?>
            </div>
            
		</main>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>
