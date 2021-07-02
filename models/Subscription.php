<?php
class Subscription
{
    private $conn;
    private $table = "subscription";
    public $sub_id;
    public $sub_name;
    public $sub_courses; // number of courses allowed
    public $sub_price;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(){
        $q = "INSERT INTO ".$this->table." (sub_name, sub_courses, sub_price) 
                                    Values (?, ?, ?)";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($this->sub_name, $this->sub_courses, $this->sub_price));
    }

    public function getAll()
    {
        $q = "SELECT sub_id as id, sub_name as title, sub_courses as courses_num, sub_price as price FROM ".$this->table;
        $stmt = $this->conn->query($q);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
