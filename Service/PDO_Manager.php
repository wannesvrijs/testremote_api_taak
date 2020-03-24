<?php
class PDO_Manager implements DBInterface
{
    private $PDO;
    private $MS;

    public function __construct( PDO $PDO, MessageService $MS )
    {
        $this->PDO = $PDO;
        $this->MS = $MS;

        //$this->MS->AddMessage("CONSTRUCT PDO_Manager");
    }

    public function GetData( $sql )
    {
        $stm = $this->PDO->prepare($sql);
        $stm->execute();

        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function ExecuteSQL( $sql )
    {
        $stm = $this->PDO->prepare($sql);

        if ( $stm->execute() ) return true;
        else return false;
    }
}