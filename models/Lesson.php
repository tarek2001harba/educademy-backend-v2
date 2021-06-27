<?php
class Lesson{
    private $conn = null;
    private $table = 'course';
    public $lesson_id;
    public $chapter_id;
    public $lesson_title;
    public $lesson_content;
    public $lesson_resource;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(){
        $q = "INSERT INTO ".$this->table." (chapter_id, lesson_title,
                                            lesson_content, lesson_resource) VALUES (
                                            :cid, :title, :content, :res)";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array(
            ':cid' => $this->chapter_id,
            ':title' => $this->lesson_title,
            ':content' => $this->lesson_content,
            ':res' => $this->lesson_resource
        ));
        return true;
    }
}