<?php
require_once "autoload.php";

$US = new UploadService( $messageService = $MS );
$US->setDBM( $DBM );
$US->UploadProfielFotos();

header( "Location: " . $_application_folder . "/profiel.php" );
?>