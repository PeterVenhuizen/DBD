<?php 
	require_once('assets/config.php'); 

    unset($_SESSION['user']); 

    //header("Location: http://www.debalderin.wur.nl/"); 
    header("Location: http://localhost/DBD/");
    die("Redirecting to: index.php");
