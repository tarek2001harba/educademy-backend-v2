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
                                            course_language, level_id, ctg_id,
                                            course_period, course_thumb) VALUE (
                                            :teacher, :title, :desc, :lang, :level,
                                            :ctg, :period, :thumb)";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            ':teacher' => $this->course_teacher,
            ':title' => $this->course_title,
            ':desc' => $this->course_desc,
            ':lang' => $this->course_lang,
            ':level' => $this->course_level,
            ':ctg' => $this->course_ctg,
            ':period' => $this->course_period,
            ':thumb' => $this->course_thumb
        ));
        // gets inserted row id for further operations
        $this->course_id = $this->conn->lastInsertId();
        return $exec;
    }

    // get all courses :offset = pagination var, 
    public function getAll($offset)
    {
        // get courses
        $course_q = "SELECT course_id, teacher_id as tid, course_title as title, course_desc as `description`,
                     course_language as `language`, level_id, ctg_id,
                     course_period as `period`, course_thumb as `thumb` FROM ".$this->table." LIMIT 10 OFFSET ".$offset;
        $course_stmt = $this->conn->query($course_q);
        $courses = $course_stmt->fetchALl(PDO::FETCH_ASSOC);

        // get chapters query
        $chapter_q = "SELECT chapter_id , chapter_title as `title`, chapter_desc as `description` 
                     FROM chapter WHERE course_id = ?";
        $chapter_stmt = $this->conn->prepare($chapter_q);

        // get lessons query
        $lesson_q = "SELECT lesson_id, lesson_title as `title`, lesson_content as `content`,
        lesson_resource as `resource`, lesson_video as `video` FROM lesson WHERE chapter_id = ?";
        $lesson_stmt = $this->conn->prepare($lesson_q);
        
        // creating tha courses array that will be then converted to JSON and sent
        // structure:
        // { // courses array
        //     [  // course
        //         ...thumb, // course info
        //         chapters : [ // course chapters
        //             [
        //                 ...decription, // chapter info
        //                 lessons : [    //chapter lessons
        //                     [...video], // lesson info
        //                     ...
        //                 ]
        //             ]
        //         ]
        //     ], 
        //     ...
        // }
        foreach($courses as &$course){
            $cid = intval($course['course_id']);
            $chapter_stmt->bindParam(1, $cid, PDO::PARAM_INT);
            $chapter_stmt->execute();
            $course['chapters'] = $chapter_stmt->fetchAll(PDO::FETCH_ASSOC);
            // get lessons
            foreach($course['chapters'] as &$chapter){
                $ch_id = intval($chapter['chapter_id']);
                $lesson_stmt->bindParam(1, $ch_id, PDO::PARAM_INT);
                $lesson_stmt->execute();
                $chapter['lessons'] = $lesson_stmt->fetchAll(PDO::FETCH_ASSOC);
            }; 
        };
        print_r($courses);
        return $courses;
    }

    public function enroll($student_id)
    {
        $q = "INSERT INTO registration (course_id, student_id) VALUE
                                       (?, ?)";
        $stmt = $this->conn->prepare($q);
        $exec = $stmt->execute(array(
            $this->course_id,
            $student_id
        ));
        return $exec;
    }
}