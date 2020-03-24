<?php
require_once "autoload.php";

$US = new UploadService( $messageService = $MS );
$US->Upload();

header( "Location: " . $_application_folder . "/file_upload.php" );
?>