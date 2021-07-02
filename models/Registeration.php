<?php
class Registeration
{
    private $conn = null;
    private $table = 'registration';
    public $reg_id;
    public $reg_date;
    public $student_id;
    public $course_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function enroll()
    {
        $q = "INSERT INTO ".$this->table." (student_id, course_id) VALUE
                                        (?, ?)";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            $this->student_id,
            $this->course_id
        ));
        return $exec;
    }
    
    public function unenroll()
    {
        $q = "DELETE FROM ".$this->table." WHERE student_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            $this->student_id,
            $this->course_id
        ));
        return $exec;
    }

    public function allowUnenroll(){
        $q = "SELECT DATE(CURRENT_TIMESTAMP) - reg_date as date FROM ".$this->table." WHERE student_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($this->student_id, $this->course_id));
        $allow = intval($stmt->fetchColumn()) >= 100 ? true : false;
        return $allow;
    }

    public function countRows(){
        $count_q = "SELECT COUNT(*) FROM ".$this->table." WHERE student_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($count_q);
        $stmt->execute(array($this->student_id, $this->course_id));
        $count = $stmt->fetchColumn();
        return intval($count);
    }
}
