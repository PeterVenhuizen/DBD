<?php require_once('../assets/config.php'); ?>

<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Banners</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="../assets/css/cms_style.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="<?=$config['absolute_path']?>assets/img/dbd_logo.ico" >        
        <style type="text/css">
            #banners { width: 1200px; margin: 0 auto; }
            #banners h2 { cursor: pointer; margin: 0.5em 0 0.2em 0; }
            #banners .explanation { text-align: justify; margin-bottom: 0.5em; }
            .banner_form { overflow: hidden; width: 100%; position: relative; margin-bottom: 0.5em; border: 1px solid #e1e1e1; padding: 0.5em; }
            #banners label { display: inline-block; width: 150px; vertical-align: top; }
            #banners input[type="text"] { margin-bottom: 2px; border: 1px solid #e1e1e1; font-size: 1em; height: 16px; line-height: 14px; padding: 3px; width: 250px; }
            .banner_form textarea { border: 1px solid #e1e1e1; width: 250px; height: 200px; font-size: 1em; font-family: 'Lato', sans-serif; padding: 3px; }
            .banner_form .img_preview { width: 750px; position: absolute; top: 0.5em; right: 0.5em; }
            .submit_banner_change { position: absolute; bottom: 1em; right: 0.5em; }
            
            #vcup_banner_form { width: 100%; overflow: hidden; margin-bottom: 0.5em; position: relative; border: 1px solid #e1e1e1; padding: 0.5em; }
            
            #upload_banner { padding: 0.5em; border: 1px solid #e1e1e1; margin-bottom: 1em; }
            .banner_preview { display: inline-block; margin-right: 0.5em; }
            .banner_preview:hover { opacity: 0.5; -moz-opacity: 0.5; filter:alpha(opacity=50); }
            .banner_preview h3 { margin: 0; font-size: 0.9em; }
            .banner_preview img { width: 275px; }
        </style>
        <script>
            $(document).ready(function () {              
                
                // Toggle home banner forms
                $(document).on('click', '#home_banner', function() {
                    $('.banner_form').toggle();
                }); 
                $(document).on('click', '#vcup_banner_h2', function() {
                    $('#vcup_banner_form').toggle();
                });                 
                
                // Change banner
                $(document).on('click', '.submit_banner_change', function (e) {
                    e.preventDefault();
                    var id = $(this).attr('id'),
                        title = $(this).parent().find('.banner_title').val(),
                        desc = $(this).parent().find('.banner_desc').text(),
                        url = $(this).parent().find('.banner_url').val(),
                        url_text = $(this).parent().find('.banner_url_text').val(),
                        img = $(this).parent().find('.banner_img option:selected').val();
                    $.post('actions/ajax.changeBanner.php', { id: id, title: title, desc: desc, url: url, url_text: url_text, img: img }).done(function () {
                        location.reload();
                    });
                });
                
                // Remove file                
                $(document).on('click', '.banner_preview', function () {
                    var filename = $(this).children('img').attr('id');
                    if (confirm("Weet je zeker dat je deze foto wilt verwijderen?")) {
                        $.post('actions/ajax.deleteBanner.php', {filename: filename}).done(function () {
                            location.reload();
                        });
                    }
                });
            }); 
        </script>
	<head>		

	<body>
		
		<!-- Menu -->
		<?php include('cms.menu.html'); ?>

<?php 
     
    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) { 
        // If they are not, we redirect them to the login page. 
        header("Location: cms.login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
    
	if ($_SESSION['user']['rights'] < 1) {
    	echo 'Je beschikt niet over de vereiste rechten om deze pagina te bewerken! <a href="cms.logout.php"> Logout</a>';
    } else {
		// Everything below this point in the file is secured by the login system 
	 
		// We can display the user's username to them by reading it from the session array.  Remember that because 
		// a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 	
			
		<!-- Main CMS content -->
		<section id="cmswrapper">
		
            <section id="banners">
                <section id="photo_banner">
                    <h2 id="home_banner">Home Foto Banner</h2>                    
                    <div class="explanation">Hier kan je de banners op de homepagina wijzigen. Je kan de titel, beschrijving, link en de afbeelding wijzigen. Onder aan de pagina kun je bekijken welke banners al beschikbaar zijn. Indien je graag een nieuwe banner wilt gebruiken, dan kan je deze evengoed onder aan de pagina uploaden. De banner afbeeldingen moeten 1000px breed en 360px hoog zijn. De banners worden op de website weergegeven in de volgorde zoals ze ook op deze pagina staan.</div>
                    
                    <!-- View/Edit the home photo banner -->
                    <?php
                        try {
                            $stmt = $db->prepare("SELECT * FROM banners WHERE banner_type = 'home_banner' ORDER BY id LIMIT 3");
                            $stmt->execute();
                            if ($stmt->rowCount() > 0) {
                                foreach ($stmt as $row) {
                                    echo '  <form id="' . $row['id'] . '" class="banner_form" method="POST">
                                                
                                                <label for="banner_title">Titel</label>
                                                <input type="text" name="banner_title" class="banner_title" value="' . $row['title'] . '">
                                                
                                                <br>
                                                
                                                <label for="banner_desc">Beschrijving</label>
                                                <textarea name="banner_desc" class="banner_desc">' . $row['description'] . '</textarea>
                                                
                                                <br>
                                                
                                                <label for="banner_url">Link</label>
                                                <input type="text" name="banner_url" class="banner_url" value="' . $row['url'] . '">
                                                
                                                <br>
                                                
                                                <label for="banner_url_text">Link beschrijving</label>
                                                <input type="text" name="banner_url_text" class="banner_url_text" value="' . $row['url_text'] . '">
                                                
                                                <br>
                                                
                                                <label for="banner_img">Afbeelding</label>';                                
                                                $ext_allowed = array("jpg", "jpeg", "gif", "png");
                                                $forbidden = array(".", "..", ".DS_Store");
                                                $path = "../assets/img/photo_banner/";

                                                if ($handle = opendir($path)) {
                                                    echo '<select name="banner_img" class="banner_img">';
                                                    while (false !== ($entry = readdir($handle))) {
                                                        $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                                                        if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
                                                            echo '  <option value="' . $entry . '"' . ($row['img'] == $entry ? 'SELECTED' : null) . '>' . $entry . '</option>';
                                                        }
                                                    }
                                                    echo '</select>';
                                                }                                               
                                    echo '      <br>
                                                
                                                <!--<img src="' . $config['absolute_path'] . 'assets/img/photo_banner/' . $row['img'] . '">-->
                                                <img src="http://www.debalderin.wur.nl/assets/img/photo_banner/' . $row['img'] . '" class="img_preview">
                                                
                                                <br>
                                                
                                                <input type="submit" id="' . $row['id'] . '" value="Verander" class="submit_banner_change">
                                                
                                            </form>';
                                }
                            }
                        } catch (PDOException $ex) { die(); }
                    ?>
                </section>
                
                <section id="vcup_banner">
                    <h2 id="vcup_banner_h2">Veluwecup Banner</h2>
                    <div class="explanation">Hier kan je de banner van de Veluwecup website wijzigen. Selecteer een van de reeds aanwezige banners, of upload een nieuwe afbeelding onder aan de pagina.</div>
                    
                    <!-- View/Edit the Veluwecup banner image -->
                    <?php    
                        try {
                            $stmt = $db->prepare("SELECT id, img FROM banners WHERE banner_type = 'vcup_banner' LIMIT 1");
                            $stmt->execute();
                            if ($stmt->rowCount() > 0) {
                                $row = $stmt->fetch();
                                echo '  <form id="vcup_banner_form" method="POST">

                                            <label for="banner_img">Afbeelding</label>';
                                            $ext_allowed = array("jpg", "jpeg", "gif", "png");
                                            $forbidden = array(".", "..", ".DS_Store");
                                            $path = "../assets/img/photo_banner/";

                                            if ($handle = opendir($path)) {
                                                echo '<select name="banner_img" class="banner_img">';
                                                while (false !== ($entry = readdir($handle))) {
                                                    $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                                                    if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
                                                        echo '  <option value="' . $entry . '"' . ($row['img'] == $entry ? 'SELECTED' : null) . '>' . $entry . '</option>';
                                                    }
                                                }
                                                echo '</select>';
                                            }        

                                  echo '    <br>

                                            <!--<img src="' . $config['absolute_path'] . 'assets/img/photo_banner/' . $row['img'] . '">-->
                                            <img src="' . $config['absolute_path'] . 'assets/img/photo_banner/' . $row['img'] . '" class="img_preview">

                                            <br>

                                            <input type="submit" id="' . $row['id'] . '" value="Verander" class="submit_banner_change">

                                        </form>';
                            }
                        } catch (PDOException $ex) { die(); }
                    ?>
                </section>
                
                <section id="banner_overview">
                    <h2 id="preview_banners">Banner Overzicht</h2>
                    <div class="explanation">Hier zie je alle banners die al aanwezig zijn op de server, zodat je makkelijk een banner uit kan kiezen. Je kan ook een nieuwe banner uploaden en deze daarna dan toevoegen aan de homepagina of als Veluwecup-banner instellen.</div>
                    
                    <form id="upload_banner" method="POST" action="cms.banners.php" enctype="multipart/form-data">
                        Selecteer afbeelding: 
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <input type="submit" value="Upload afbeelding" name="upload_image">
                    </form>
                    
                    <?php
                        // Upload image
                        if (isset($_POST['upload_image'])) {
                            
                            $path = "../assets/img/photo_banner/";
                            $allowed = array('png', 'jpg', 'jpeg', 'gif');
                            $extension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);

                            if(!in_array(strtolower($extension), $allowed)){
                                echo '{"status":"error"}';
                                exit;
                            }

                            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $path.$_FILES['fileToUpload']['name'])){
                                echo '{"status":"success"}';
                                header('Location: http://www.debalderin.wur.nl/cms/cms.banners.php');
                            }
                        }
                    ?>
                    
                    <!-- View current banner images and upload new images -->
                    <?php
                        $ext_allowed = array("jpg", "jpeg", "gif", "png");
                        $forbidden = array(".", "..", ".DS_Store");
                        $path = "../assets/img/photo_banner/";
        
                        if ($handle = opendir($path)) {
                            while (false !== ($entry = readdir($handle))) {
                                $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                                if (!in_array($entry, $forbidden) && in_array($extension, $ext_allowed)) {
                                    echo '  <div class="banner_preview">
                                                <h3>' . $entry . '</h3>
                                                <img src="' . $path . $entry . '" id="' . $entry . '">
                                            </div>';
                                }
                            }
                        }   
                    ?>                  
                    
                </section>
            </section>

		<!-- Footer -->
		<?php include('cms.footer.php'); ?>
			
		</section>
	</body>
</html>

<?php
	}
?>
