<?php
$login_form = true;
require_once "autoload.php";

$formname = $_POST["formname"];
$buttonvalue = $_POST['loginbutton'];

if ( $formname == "login_form" AND $buttonvalue == "Log in" )
{
    if ( $Auth->CheckLogin($_POST['usr_login'], $_POST['usr_paswd']) )
    {
        $MS->AddMessage( "Welkom, " . $_SESSION['usr']->getVoornaam() . "!" );
        header("Location: " . $_application_folder . "/steden.php");
    }
    else
    {
        $MS->AddMessage( "Sorry! Verkeerde login of wachtwoord!", "error" );
        header("Location: " . $_application_folder . "/login.php");
    }
}
else
{
    $MS->AddMessage( "Foute formname of buttonvalue", "error" );
}
?>