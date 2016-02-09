<!DOCTYPE html>
	
	<head>
		<title>Image Browser</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>		
		<script type='text/javascript'>
			$(document).ready(function() {
				$('a').click(function() {
					var cback = $(this).attr('class');
					var imgfilename = $(this).attr('id');
					window.opener.CKEDITOR.tools.callFunction(cback, 'http://www.debalderin.wur.nl/images/newsImages/'+imgfilename);
					window.close();
				});
			});
		</script>
		<style>
			.portrait {
				height: 200px;
				float: left;
				margin: 5px;
			}
			.landscape {
				height: 200px;
				float: left;
				margin: 5px;
			}
			h1 {
				font-family: 'Lato', sans-serif;
				color: rgb(241, 36, 36);
				font-weight: bold;				
				margin: 0;
				padding: 0;
			}
		</style>
	</head>
	
	<body>
		<h1>Browse images</h1>
	<?php
		$cback = $_GET['CKEditorFuncNum'];
		$ext_allowed = array("jpg", "jpeg", "gif", "png");
		$path = '../../images/newsImages/';
		if ($handle = opendir($path)) {
			while (false !== ($entry = readdir($handle))) {
				$extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
				if (in_array($extension, $ext_allowed)) {
					list($w, $h) = getimagesize($path.$entry);
					if ($h < $w) { $class = 'landscape'; } 
					else { $class = 'portrait'; }
					echo '	<a href="" class="' . $cback . '" id="' . $entry . '"><img src="' . $path . $entry . '" class="' . $class . '"/></a>';
				} 
			}
		}		
	?>
	</body>
	
</html>