<?php
class ApiActions{
    private $pdo;
    private $tablename = 'taak';

    /**
     * @return string
     */
    public function getTablename()
    {
        return $this->tablename;
    }

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function read($id = null, $date = null)
    {
        //read single taak
        if (isset($id)) {
            //clean input
            $id = htmlentities($id);

            // make querry string
            $query = "SELECT * FROM " . $this->getTablename() . " where taa_id = :taa_id";

            //prepare statement
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':taa_id', $id);
        }

        //read single taak by date
        elseif (isset($date)) {
            //clean input
            $date = htmlentities($date);

            // make querry string
            $query = "SELECT * FROM " . $this->getTablename() . " where taa_datum = :taa_date";

            //prepare statement
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':taa_date', $date);

        //read all taken
        } else {
            // make querry string
            $query = "SELECT * FROM ". $this->getTablename();

            //prepare statement
            $stmt = $this->pdo->prepare($query);
        }

        //execute statement
        $stmt->execute();

        //get row count
        $rowcount = $stmt->rowCount();

        if ($rowcount > 0){

            //fetch data from database
            $taak_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($taak_arr, $row);
            }

            //encode data to json
            echo json_encode($taak_arr);

        } else {
            echo 'no taak found';
        }

    }


    public function create(){

        // make querry string
        $query = "INSERT INTO ". $this->getTablename() ." SET taa_omschr = :taa_omschr, taa_datum = :taa_datum";

        //get raw posted data
        $data = json_decode(file_get_contents('php://input'));

        //prepare statement
        $stmt = $this->pdo->prepare($query);

        //check if fields are sett
        if (!isset($data->taa_omschr) or !isset($data->taa_datum)) {
            echo 'fill in all necessary fields';
            die();
        //clean user-input
        } else {
            $taa_omschr    = htmlentities($data->taa_omschr);
            $taa_datum     = htmlentities($data->taa_datum);
        }

        //bind parameters
        $stmt->bindParam(':taa_omschr', $taa_omschr);
        $stmt->bindParam(':taa_datum', $taa_datum);


        //create new taak in db
        if ($stmt->execute()) echo 'taak created.';
        else {
            echo $stmt->errorInfo();
            echo 'taak creation failed';
        }


    }

    public function update($id){
        // make querry string
        $query = "UPDATE ". $this->getTablename() ." SET taa_omschr = :taa_omschr, taa_datum = :taa_datum where taa_id = :taa_id";

        //get raw posted data
        $data = json_decode(file_get_contents('php://input'));

        //prepare statement
        $stmt = $this->pdo->prepare($query);

        //clean user-input
        $taa_id       = htmlentities($id);
        $taa_omschr    = htmlentities($data->taa_omschr);
        $taa_datum     = htmlentities($data->taa_datum);

        //bind parameters
        $stmt->bindParam(':taa_id', $taa_id);
        $stmt->bindParam(':taa_omschr', $taa_omschr);
        $stmt->bindParam(':taa_datum', $taa_datum);

        //update taak in db
        if ($stmt->execute()) echo 'taak updated.';
        else {
            echo $stmt->errorInfo();
            echo 'taak update failed';
        }
    }

    public function delete($id)
    {
        // make querry string
        $query = "DELETE FROM ". $this->getTablename() ." where taa_id = :taa_id";

        //prepare statement
        $stmt = $this->pdo->prepare($query);

        //clean user-input
        $taa_id = htmlentities($id);

        //bind parameters
        $stmt->bindParam(':taa_id', $taa_id);

        //delete taak in db
        if ($stmt->execute()) echo 'taak deleted.';
        else {
            echo $stmt->errorInfo();
            echo 'taak deletion failed';
        }
    }

    public function callAPI($method, $url, $data){
        $curl = curl_init();
        switch ($method){
            case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
        }
}