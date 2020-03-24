<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 20/02/2020
 * Time: 10:27
 */

class Authentication
{
    private $DBM;
    private $MS;

    public function __construct( PDO_Manager $DBM, MessageService $MS )
    {
        $this->DBM = $DBM;
        $this->MS = $MS;
    }

    /**
     * @param PDO_Manager $DBM
     */
    public function setDBM( PDO_Manager $DBM )
    {
        $this->DBM = $DBM;
    }

    public function CheckLogin( $login, $paswd )
    {
        //gebruiker opzoeken ahv zijn login (e-mail)
        $sql = "SELECT * FROM users WHERE usr_login='" . $login . "' ";
        $data = $this->DBM->GetData($sql);

        if ( count($data) == 1 )
        {
            $row = $data[0];
            //password controleren
            if ( password_verify( $paswd, $row['usr_paswd'] ) ) $login_ok = true;
        }

        if ( $login_ok )
        {
            session_start();
            $User = new User();
            $User->Load($row);

            $_SESSION['usr'] = $User;
            $this->LogLoginUser();

            return true;
        }

        return false;
    }

    public function LogLoginUser()
    {
        $session = session_id();
        $timenow = new DateTime( 'NOW', new DateTimeZone('Europe/Brussels') );
        $now = $timenow->format('Y-m-d H:i:s') ;
        $sql = "INSERT INTO log_user SET log_usr_id=".$_SESSION['usr']->getId().", log_session_id='".$session."', log_in= '".$now."'";
        $this->DBM->ExecuteSQL($sql);
    }

    public function LogLogoutUser()
    {
        $session = session_id();
        $timenow = new DateTime( 'NOW', new DateTimeZone('Europe/Brussels') );
        $now = $timenow->format('Y-m-d H:i:s') ;
        $sql = "UPDATE log_user SET  log_out='".$now."' where log_session_id='".$session."'";
        $this->DBM->ExecuteSQL($sql);
    }

    public function CheckIfUserExistsAlready()
    {
        //controle of gebruiker al bestaat
        $sql = "SELECT * FROM users WHERE usr_login='" . $_POST['usr_login'] . "' ";
        $data = $this->DBM->GetData($sql);
        if ( count($data) > 0 ) die("Deze gebruiker bestaat reeds! Gelieve een andere login te gebruiken.");
    }

    public function ValidatePostedUserData()
    {
        $this->CheckIfUserExistsAlready();

        //controle wachtwoord minimaal 8 tekens
        if ( strlen($_POST["usr_paswd"]) < 8 ) die("Uw wachtwoord moet minstens 8 tekens bevatten!");

        //controle geldig e-mailadres
        if (!filter_var($_POST["usr_login"], FILTER_VALIDATE_EMAIL)) die("Ongeldig email formaat voor login");
    }

    public function RegisterUser( $_application_folder )
    {
        //wachtwoord coderen
        $password_encrypted = password_hash ( $_POST["usr_paswd"] , PASSWORD_DEFAULT );

        $sql = "INSERT INTO users SET " .
            " usr_voornaam='" . htmlentities($_POST['usr_voornaam'], ENT_QUOTES) . "' , " .
            " usr_naam='" . htmlentities($_POST['usr_naam'], ENT_QUOTES) . "' , " .
            " usr_straat='" . htmlentities($_POST['usr_straat'], ENT_QUOTES) . "' , " .
            " usr_huisnr='" . htmlentities($_POST['usr_huisnr'], ENT_QUOTES) . "' , " .
            " usr_busnr='" . htmlentities($_POST['usr_busnr'], ENT_QUOTES) . "' , " .
            " usr_postcode='" . htmlentities($_POST['usr_postcode'], ENT_QUOTES) . "' , " .
            " usr_gemeente='" . htmlentities($_POST['usr_gemeente'], ENT_QUOTES) . "' , " .
            " usr_telefoon='" . htmlentities($_POST['usr_telefoon'], ENT_QUOTES) . "' , " .
            " usr_login='" . $_POST['usr_login'] . "' , " .
            " usr_paswd='" . $password_encrypted . "'  " ;

        if ( $this->DBM->ExecuteSQL($sql) )
        {
            $this->MS->AddMessage( "Bedankt voor uw registratie!" );

            if ( $this->CheckLogin( $_POST['usr_login'], $_POST['usr_paswd']) )
            {
                header("Location: " . $_application_folder . "/steden.php");
            }
            else
            {
                $this->MS->AddMessage( "Sorry! Verkeerde login of wachtwoord na registratie!", "error" );
                header("Location: " . $_application_folder . "/login.php");
            }
        }
        else
        {
            $this->MS->AddMessage( "Sorry, er liep iets fout. Uw gegevens werden niet goed opgeslagen", "error" ) ;
        }
    }

}