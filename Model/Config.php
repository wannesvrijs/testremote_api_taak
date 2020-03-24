<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 18/02/2020
 * Time: 14:16
 */

class Config
{
    private $application_folder = "/testremote_api_taak";
    private $root_folder;

    private $db_host = "localhost";
    private $db_database = "steden";
    private $db_user = 'root';
    private $db_pass = 'mysql';


    public function __construct( $path )
    {
        $this->application_folder = $path;
        $this->root_folder = $_SERVER['DOCUMENT_ROOT'] . $this->application_folder ;
    }

    /**
     * @return string
     */
    public function getDbHost()
    {
        return $this->db_host;
    }

    /**
     * @return string
     */
    public function getDbDatabase()
    {
        return $this->db_database;
    }


    /**
     * @return string
     */
    public function getApplicationFolder()
    {
        return $this->application_folder;
    }

    /**
     * @return string
     */
    public function getRootFolder()
    {
        return $this->root_folder;
    }

    /**
     * @return string
     */
    public function getDbDsn()
    {
        return 'mysql:host=' . $this->getDbHost() . ';dbname=' . $this->getDbDatabase();
    }

    /**
     * @return string
     */
    public function getDbUser()
    {
        return $this->db_user;
    }

    /**
     * @return string
     */
    public function getDbPass()
    {
        return $this->db_pass;
    }

}