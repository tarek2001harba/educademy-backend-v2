<?php
class Level{
    private $conn = null;
    private $table = 'skillLevel';
    public $level_id;
    public $level_name;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function get(){
        $q = "SELECT level_id, level_name FROM ".$this->table;
        $res = $this->conn->query($q);
        $levels = $res->fetchAll(PDO::FETCH_NUM);
        return $levels;
    }
}