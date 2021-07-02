<?php
class Purchase
{
    private $conn;
    private $table = "purchase";
    public $purchase_id;
    public $purchase_date;
    public $student_id; 
    public $sub_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    // 0: unsuccesful 1: successful 2: already purchased
    public function make(){
        if($this->countRows() === 0){
            $q = "INSERT INTO ".$this->table." (student_id, sub_id) 
                                        Values (?, ?)";
            $stmt = $this->conn->prepare($q);
            $exec = $stmt->execute(array($this->student_id, $this->sub_id));
            $res = $exec ? 1 : 0;
            return $res;
        }
        return false;
    }

    public function cancel()
    {
        $q = "DELETE FROM ".$this->table." WHERE student_id = ?";
        $check = $this->conn->prepare($q);
        $exec = $check->execute(array($this->student_id));
        return $exec;
    }

    // public function getAll()
    // {
    //     $q = "SELECT sub_id as id, sub_name as title, sub_courses as courses_num, sub_price as price FROM ".$this->table;
    //     $stmt = $this->conn->query($q);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    
    public function countRows(){
        $count_q = "SELECT COUNT(*) FROM ".$this->table." WHERE student_id = ?";
        $stmt = $this->conn->prepare($count_q);
        $stmt->execute(array($this->student_id));
        $count = $stmt->fetchColumn();
        return intval($count);
    }

    public function getStudentSub(){
        $q = "SELECT sub_id FROM ".$this->table." WHERE student_id = ".$this->student_id;
        $sub = $this->conn->query($q)->fetchColumn();
        if($this->countRows("student_id = ".$this->student_id) === 0){
            return -1;
        }
        return intval($sub);
    }
}
