<?php
class Student extends User{
    private $conn;
    private $table = 'student';
    public $sid;
    public $sub_id;
    
    function __construct($db){
        parent::__construct($db);
        $this->conn = $db;
    }
    public function create(){
        $user_res = parent::create();
        // if user was added successfully then it will add it to teacher table
        if($user_res){
            $q = "INSERT INTO ".$this->table." (user_id) VALUE (?)";
            $stmt = $this->conn->prepare($q);
            $exec = $stmt->execute(array($this->uid));
            $this->setSID();
            return $exec;
        }
        return false;
    }
    
    public function signinAuth() {
        parent::signinAuth();
        $this->setSID();
        return true;
    }
    // gets the added teacher id for further operatoins
    public function setSID(){
        $sid_q = "SELECT student_id FROM ".$this->table." WHERE user_id = ?";
        $sid_stmt = $this->conn->prepare($sid_q);
        $sid_stmt->execute(array($this->uid));
        $this->sid = $sid_stmt->fetch()[0];
    }
}