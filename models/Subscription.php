<?php
class Subscription
{
    private $conn;
    private $table;
    public $sub_id;
    public $sub_name;
    public $sub_courses; // number of courses allowed
    public $sub_price;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(){
        $q = "INSERT INTO ".$this->table." (sub_name, sub_course, sub_price) 
                                    Values (?, ?, ?)";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($this->sub_name, $this->sub_courses, $this->sub_price));
    }
}
