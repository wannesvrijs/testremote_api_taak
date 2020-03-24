<?php
class UploadService
{
    private $DBM;
    private $MS;

    private $target_dir = "../img/";                                           //de map waar de afbeelding uiteindelijk moet komen; relatief pad tov huidig script
    private $max_size = 5000000;                                                           //maximum grootte in bytes
    private $allowed_extensions = [ "jpeg", "jpg", "png", "gif" ];       //toegelaten bestandsextensies

    // Constructor Injection
    public function __construct( MessageService $messageService )
    {
        $this->MS = $messageService;
    }

    /**
     * @param PDO_Manager $DBM
     */
    // Setter Injection
    public function setDBM(PDO_Manager $DBM)
    {
        $this->DBM = $DBM;
    }

    public function Upload()
    {
        if ( isset($_POST["submit"]) AND $_POST["submit"] == "Opladen" ) //als het juiste form gesubmit werd
        {
            //overloop alle bestanden in $_FILES
            foreach ( $_FILES as $f )
            {
                $upfile = array();
                $upfile["name"]                            = basename($f["name"]);
                $upfile["tmp_name"]                    = $f["tmp_name"];
                $upfile["target_path_name"]       = $this->target_dir . $upfile["name"]; //samenstellen definitieve bestandsnaam (+pad)    //basename
                $upfile["extension"]                      = pathinfo($upfile["name"], PATHINFO_EXTENSION);
                $upfile["getimagesize"]                = getimagesize($upfile["tmp_name"]);                 //getimagesize geeft false als het bestand geen afbeelding is
                $upfile["size"]                                = $f["size"];

                $result = $this->CheckUploadedFile( $upfile, $check_real_image = true, $check_if_exists = false, $check_max_size = true,
                                                                                    $check_allowed_extensions = true );

                if ( !$result ) $this->MS->AddMessage("Sorry, your file was not uploaded.", "error");
                else
                {
                    //bestand verplaatsen naar definitieve locatie + naam
                    if ( move_uploaded_file( $upfile["tmp_name"], $upfile["target_path_name"] ))
                    {
                        $this->MS->AddMessage( "The file " . $upfile["name"] . " has been uploaded as " . $upfile["target_path_name"] ) ;
                    }
                    else
                    {
                        $this->MS->AddMessage( "Sorry, there was an unexpected error uploading file " . $upfile["name"], "error" );
                    }
                }
            }
        }
    }


    function CheckUploadedFile( $upfile, $check_real_image = true, $check_if_exists = true, $check_max_size = true, $check_allowed_extensions = true )
    {
        $returnvalue = true;

        // Check if image file is a actual image or fake image
        if ( $check_real_image AND $upfile["getimagesize"] === false )
        {
            $this->MS->AddMessage( "File " . $upfile["name"] . " is not an image.", "error" ); $returnvalue = false;
        }

        // Check if file already exists
        if ( $check_if_exists AND file_exists($upfile["target_path_name"]))
        {
            $this->MS->AddMessage( "File  " . $upfile["name"] . " already exists.", "error" ); $returnvalue = false;
        }

        // Check file size
        if ( $check_max_size AND $upfile["size"] > $this->max_size )
        {
            $this->MS->AddMessage( "File  " . $upfile["name"] . "  is too large.", "error"); $returnvalue = false;
        }

        // Allow only certain file formats
        if ( $check_allowed_extensions )
        {
            if ( ! in_array( $upfile["extension"], $this->allowed_extensions) )
            {
                $this->MS->AddMessage( "Wrong extension. Only " . implode(", ", $this->allowed_extensions) . " files are allowed.", "error") ;
                $returnvalue = false;
            }
        }
        return $returnvalue;
    }

    function UploadProfielFotos()
    {
        if ( isset($_POST["submit"]) == "Opladen" )
        {
            $images = array();

            //pasfoto, eid_voorzijde en eid_achterzijde overlopen
            foreach ( $_FILES as $inputname => $fileobject )   //overloop alle bestanden in $_FILES
            {
                $tmp_name= $fileobject["tmp_name"];
                $originele_naam = $fileobject["name"];
                $size = $fileobject["size"];
                $extensie = pathinfo($originele_naam, PATHINFO_EXTENSION);

                $target = "";

                //CONTROLES
                $max_size = 20000000; //maximum grootte in bytes
                $cancel = false;

                //grootte
                if ( $size > $max_size )
                {
                    $this->MS->AddMessage( "Bestand " . $originele_naam . " is te groot (" . $size . " bytes). Maximum $max_size bytes!", "error");
                    $cancel = true;
                }

                //toegelaten extensies
                if ( ! in_array( pathinfo($originele_naam, PATHINFO_EXTENSION), $this->allowed_extensions ))
                {
                    $this->MS->AddMessage( "Bestand " . $originele_naam . ": verkeerde bestandsextensie!", "error");
                    $cancel = true;
                }

                //is het bestand wel echt een afbeelding?
                if ( getimagesize($tmp_name) === false)
                {
                    $this->MS->AddMessage( "Bestand " . $originele_naam . " is niet echt een afbeelding!", "error");
                    $cancel = true;
                }

                $usr_id = $_SESSION["usr"]->getId();

                if ( ! $cancel )
                {
                    switch ( $inputname )
                    {
                        case "pasfoto":
                            $target = "pasfoto_$usr_id.$extensie";
                            $images[] = "usr_pasfoto='" . $target . "'";
                            break;
                        case "eidvoor":
                            $target = "eidvoor_$usr_id.$extensie";
                            $images[] = "usr_vz_eid='" . $target . "'";
                            break;
                        case "eidachter":
                            $target = "eidachter_$usr_id.$extensie";
                            $images[] = "usr_az_eid='" . $target . "'";
                            break;
                    }

                    $target = $this->target_dir . $target;

                    //bestand verplaatsen naar definitieve locatie
                    $this->MS->AddMessage( "Moving " . $inputname . " to " . $target );

                    if ( move_uploaded_file( $tmp_name, $target))
                    {
                        $this->MS->AddMessage( "Bestand $originele_naam opgeladen" );
                    }
                    else $this->MS->AddMessage( "Sorry, there was an unexpected error uploading file " . $originele_naam, "error");
                }
            }

            //de afbeeldingen opslaan in het gebruikersprofiel
            $sql = "update users SET " . implode("," , $images) . " where usr_id=$usr_id";
            $this->DBM->ExecuteSQL($sql);

            //eventueel een redirect naar de profielpagina
        }
    }

}