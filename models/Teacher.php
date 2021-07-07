<?php
class Teacher extends User{
    private $conn;
    private $table = 'teacher';
    public $tid;
    
    function __construct($db){
        parent::__construct($db);
        $this->conn = $db;
        $this->uid = parent::getUID();        
    }

    public function create(){
        $user_res = parent::create();
        // if user was added successfully then it will add it to teacher table
        if($user_res){
            $q = "INSERT INTO ".$this->table." (user_id) VALUE (?)";
            $stmt = $this->conn->prepare($q);
            $exec = $stmt->execute(array($this->uid));
            $this->tid = $this->conn->lastInsertId();
            return $exec;
        }
        return false;
    }
    
    public function signinAuth() {
        $user_res = parent::signinAuth();
        if($user_res){
            $this->tid = $this->setTID();
            return true;
        }
        return false;
    }
    
    // gets the added teacher id for further operatoins
    public function setTID() {
        $q = "SELECT teacher_id FROM ".$this->table." WHERE user_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($this->uid));
        return intval($stmt->fetch()[0]);
    }
}