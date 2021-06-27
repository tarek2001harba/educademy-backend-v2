<?php
class Course{
    private $conn = null;
    private $table = 'course';
    public $course_id;
    public $course_title;
    public $course_desc;
    public $course_teacher;
    public $course_lang;
    public $course_level;
    public $course_ctg;
    public $course_period;
    public $course_thumb;
    public $students_num;
    public $course_rate;
    public $course_published;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(){
        $q = "INSERT INTO ".$this->table." (teacher_id, course_title, course_desc,
                                            course_language, level_id, ctg_id
                                            course_period, course_thumb) VALUES (
                                            :teacher, :title, :desc, :lang, :level,
                                            :ctg, :period, :thumb
                                            )";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            ':teacher' => $this->course_teacher,
            ':title' => $this->course_title,
            ':desc' => $this->course_desc,
            ':lang' => $this->course_lang,
            ':level' => $this->course_level,
            ':ctg' => $this->course_ctg,
            ':period' => $this->course_teacher,
            ':thumb' => $this->course_thumb
        ));
        // gets inserted row id for further operations
        $this->course_id = $this->conn->lastInsertId();
        return $exec;
    }
}