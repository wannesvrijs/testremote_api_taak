<?php
class Container
{
    private $config;
    private $pdo;
    private $mysqli;

    private $DBM;
    private $MySQLi_Manager;

    private $viewService;
    private $messageService;
    private $cityLoader;
    private $flowerLoader;
    private $uploadService;
    private $authentication;
    private $taak;


    private $OWM_Service;

    /**
     * @param Config $config
     */
    public function __construct( Config $config )
    {
        $this->config = $config;

        $this->getDBM();
        $this->getMessageService();
        $this->getAuthentication();
    }

    /**
     * @return PDO
     */
    public function getPdo()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO($this->config->getDbDsn(), $this->config->getDbUser(), $this->config->getDbPass());
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }

    /**
     * @return OpenWeatherMapService
     */
    public function getOWMService()
    {
        if ($this->OWM_Service === null) {
            $this->OWM_Service = new OpenWeatherMapService();
        }

        return $this->OWM_Service;
    }

    /**
     * @return mysqli
     */
    public function getMysqli()
    {
        if ( $this->mysqli === null ) {
            $this->mysqli = new mysqli( $this->config->getDbHost(),
                                                          $this->config->getDbUser(),
                                                          $this->config->getDbPass(),
                                                          $this->config->getDbDatabase()  );
        }
        return $this->mysqli;
    }


    /**
     * @return PDO_Manager
     */
    public function getDBM()
    {
        if ( $this->DBM === null ){
            $this->DBM = new PDO_Manager( $this->getPDO(), $this->getMessageService() );
        }
        return $this->DBM;
    }

    /**
     * @return MYSQLI_Manager
     */
    public function getMySQLiManager()
    {
        if ( $this->MySQLi_Manager === null ){
            $this->MySQLi_Manager = new MYSQLI_Manager( $this->getMySQLi(), $this->getMessageService() );
        }
        return $this->MySQLi_Manager;
    }

    /**
     * @return ViewService
     */
    public function getViewService()
    {
        if ( $this->viewService === null ){
            $this->viewService = new ViewService( $this->config->getApplicationFolder() );
        }
        return $this->viewService;
    }

    /**
     * @return CityLoader
     */
    public function getCityLoader()
    {
        if ( $this->cityLoader === null ){
            $this->cityLoader = new CityLoader( $this->getDBM() );
            $this->cityLoader->injectOpenWeatherMapService( $this->getOWMService() );
        }
        return $this->cityLoader;
    }

    /**
     * @return FlowerLoader
     */
    public function getFlowerLoader()
    {
        if ( $this->flowerLoader === null ){
            $this->flowerLoader = new FlowerLoader( $this->getMySQLiManager() );
        }
        return $this->flowerLoader;
    }

    /**
     * @return MessageService
     */
    public function getMessageService()
    {
        if ( $this->messageService === null ){
            $this->messageService = new MessageService( $this->getViewService() );
        }
        return $this->messageService;
    }

    /**
     * @return UploadService
     */
    public function getUploadService()
    {
        if ( $this->uploadService === null ){
            $this->uploadService = new UploadService( $this->getMessageService() );
        }
        return $this->uploadService;
    }

    /**
     * @return Authentication
     */
    public function getAuthentication()
    {
        if ( $this->authentication === null ){
            $this->authentication = new Authentication( $this->getDBM(), $this->getMessageService() );
        }
        return $this->authentication;
    }

    /**
     * @return ApiActions
     */
    public function getApiActions()
    {
        if ($this->taak === null) $this->taak = new ApiActions($this->getPDO());
        return $this->taak;
    }
}