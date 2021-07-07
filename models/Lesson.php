<?php
class Lesson{
    private $conn = null;
    private $table = 'lesson';
    public $lesson_id;
    public $chapter_id;
    public $lesson_title;
    public $lesson_content;
    public $lesson_video;
    public $lesson_resource;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(){
        $q = "INSERT INTO ".$this->table." (chapter_id, lesson_title, lesson_content, 
                                            lesson_resource, lesson_video) VALUES (
                                            :cid, :title, :content, :res, :vid)";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            ':cid' => $this->chapter_id,
            ':title' => $this->lesson_title,
            ':content' => $this->lesson_content,
            ':res' => $this->lesson_resource,
            ':vid' => $this->lesson_video,
        ));
        return $exec;
    }

    public function get()
    {
        $q = "SELECT lesson_id as lid, chapter_id as chid, lesson_title as title, lesson_content as content, 
                lesson_resource as resources, lesson_video as video,
                (SELECT count(*) FROM lesson WHERE chapter_id = l.chapter_id) as nav_lessons,
                (SELECT lesson_id FROM lesson WHERE chapter_id = l.chapter_id LIMIT 1) as nav_flesson FROM ".$this->table." as l WHERE lesson_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array($this->lesson_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}