<?php
class Chapter{
    private $conn = null;
    private $table = 'chapter';
    public $chapter_id;
    public $course_id;
    public $chapter_title;
    public $chapter_desc;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(){
        $q = "INSERT INTO ".$this->table." (course_id, chapter_title, chapter_desc) VALUE 
                                           (:cid, :title, :desc)";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            ':cid' => $this->course_id,
            ':title' => $this->chapter_title,
            ':desc' => $this->chapter_desc
        ));
        return $exec;
    }
    public function findCID($ch_name, $course){
        $q = "SELECT chapter_id FROM ".$this->table." WHERE course_id = ? AND chapter_title = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($course, $ch_name));
        $this->chapter_id = intval($stmt->fetch()[0]);
        return $this->chapter_id;
    }
}