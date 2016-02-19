<?php
    if (isset($_POST['filename'])) {
        
        $path = $_SERVER[DOCUMENT_ROOT] . '/assets/img/photo_banner/';
        unlink($path . $_POST['filename']);
    }
?>