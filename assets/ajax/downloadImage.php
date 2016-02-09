<?php
	if (isset($_GET['file'])) {
		//$path = $_SERVER[DOCUMENT_ROOT] . '/dbd_next/';
        $path = '../../';
		$file = $path.$_GET['file'];
		
     	header('Content-Type: application/octet-stream');
     	header('Content-Disposition: attachment; filename="'.basename($file).'"');
     	readfile($file);
	}
?>
		<script> history.go(-1); </script>
<?php	