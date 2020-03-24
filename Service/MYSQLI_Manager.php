<?php
class MYSQLI_Manager implements DBInterface
{
    private $mysqli;
    private $MS;

    public function __construct( MySQLi $mysqli, MessageService $MS )
    {
        $this->mysqli = $mysqli;
        $this->MS = $MS;

        $this->MS->AddMessage("CONSTRUCT MYSQLI_Manager");
    }

    public function GetData( $sql )
    {
        $result = $this->mysqli->query($sql);
        while( $row = $result -> fetch_array(MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public function ExecuteSQL( $sql )
    {
        $result = $this->mysqli->query($sql);

        if ( $result ) return true;
        else return false;
    }
}