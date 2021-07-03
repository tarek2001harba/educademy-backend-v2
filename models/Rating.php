<?php
class Rating{
    private $conn = null;
    private $table = 'rating';
    public $rating_id;
    public $course_id;
    public $student_id;
    public $rating_comment;
    public $rating_rate;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(){
        $q = "INSERT INTO ".$this->table." (student_id, course_id, rating_rate, rating_comment) VALUE 
                                           (:sid, :cid, :rate, :cmnt)";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            ':sid' => $this->student_id,
            ':cid' => $this->course_id,
            ':rate' => $this->rating_rate,
            ':cmnt' => $this->rating_comment
        ));
        return $exec;
    }

    public function getRating()
    {
        $q = "SELECT COUNT(*) as num, rating_id as id, rating_rate as rate, rating_comment as comment FROM ".$this->table." WHERE student_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($this->student_id, $this->course_id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countRows(){
        $count_q = "SELECT COUNT(*) FROM ".$this->table." WHERE student_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($count_q);
        $stmt->execute(array($this->student_id, $this->course_id));
        $count = $stmt->fetchColumn();
        return intval($count);
    }
}