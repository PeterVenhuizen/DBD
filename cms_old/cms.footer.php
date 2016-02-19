<?php require_once('../assets/config.php'); ?>

<section id="footer">
	Je bent ingelogd als "<?php echo $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['prefix'] . ' ' . $_SESSION['user']['last_name']; ?>". <a href="cms.logout.php">Logout</a>
</section>