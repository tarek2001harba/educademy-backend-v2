<?php
class Database{
    private $dbname = 'educademy';
    private $username = 'tarek';
    private $hostname = 'localhost';
    private $password = 'T@ReK1002#aRba';
    private $conn = null;
    function __construct(){
        try{
            $this->conn = new PDO("mysql:host={$this->hostname};dbname={$this->dbname}", 
            $this->username, 
            $this->password);
        }
        catch(PDOException $ex){
            die("ERROR failed to connect to the database: ".$ex->getMessage());
        }
    }
    public function getConnection(){
        return $this->conn;
    }
}