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

    // get all courses $offset = pagination var, 
    public function getAll($offset, $filter = [])
    {
        $filter_where = !empty($filter) ? $this->filter($filter) : '';
        // get courses
        $course_q = "SELECT course_id, (SELECT CONCAT_WS(' ', user_fname, user_lname) from users
                     WHERE user_id = (SELECT user_id FROM teacher WHERE teacher_id = c.teacher_id)) as teacher, course_title as title, course_desc as `description`,
                     course_language as `language`, 
                     (SELECT level_name FROM skillLevel WHERE level_id = c.level_id) as level, 
                     (SELECT ctg_name FROM category WHERE ctg_Id = c.ctg_id) as category,
                     course_period as `period`, course_thumb as `thumb` FROM ".$this->table." as c ".$filter_where." LIMIT 10 OFFSET ".$offset;
        $res_count = $this->count($filter_where);
        $course_stmt = $this->conn->query($course_q);
        $courses = $course_stmt->fetchALl(PDO::FETCH_ASSOC);
        foreach($courses as &$course){
            $course['period'] .= intval($course['period']) === 1 ? " Month" : " Months";
        }
        return [$res_count, $courses];
    }

    // gets one course with a specific id
    public function getCourse()
    {
        $course_q = "SELECT course_id as cid, (SELECT CONCAT_WS(' ', user_fname, user_lname) from users
                     WHERE user_id = (SELECT user_id FROM teacher WHERE teacher_id = c.teacher_id)) as teacher, course_title as title, course_desc as `description`,
                     course_language as `language`, 
                     (SELECT level_name FROM skillLevel WHERE level_id = c.level_id) as level, 
                     (SELECT ctg_name FROM category WHERE ctg_Id = c.ctg_id) as category,
                     course_period as `period`, course_thumb as `thumb` FROM ".$this->table." as c WHERE course_id = ".$this->course_id;
        $course_stmt = $this->conn->query($course_q);
        $course = $course_stmt->fetch(PDO::FETCH_ASSOC);
        $this->createCourseRes($course);
        return $course;
    }
    // generates the sql for filtering
    // $filter is an array that has five params
    // :tid (teacher_id), :level, :category,
    // :language, :period
    public function filter($filter)
    {   
        $sql = " WHERE";
        $ind = 0;
        foreach($filter as $k => $f){
            if($ind > 0){
                $sql .= " AND";
            }
            switch($k){
                case "tid":
                    $sql .= " teacher_id = ".$f;
                    break;
                case "level":
                    $sql .= " level_id = ".$f;
                    break;
                case "category":
                    $sql .= " ctg_id = ".$f;
                    break;
                case "language":
                    $sql .= " course_language = '".$f."'";
                    break;
                default:
                    $sql .= " course_period ".$f;
                    break;
            };
            $ind++;
        }
        return $sql;
    }

    public function count($cond = "")
    {
        $q = "SELECT COUNT(*) FROM course ".$cond;
        $stmt = $this->conn->query($q);
        return intval($stmt->fetch()[0]);
    }
    // creating tha courses array of response that will be then converted to JSON and sent
    // structure:
    // { // course array
    //     ...thumb, // course info
    //     chapters : [ // course chapters
    //         [
    //             ...decription, // chapter info
    //             lessons : [    //chapter lessons
    //                 [...video], // lesson info
    //                 ...
    //             ]
    //         ]
    //     ]
    // }

    public function createCourseRes(&$course_res)
    {
        // get chapters query
        $chapter_q = "SELECT chapter_id , chapter_title as `title`, chapter_desc as `description` 
                     FROM chapter WHERE course_id = ?";
        $chapter_stmt = $this->conn->prepare($chapter_q);

        // get lessons query
        $lesson_q = "SELECT lesson_id, lesson_title as `title`, lesson_content as `content`,
        lesson_resource as `resource`, lesson_video as `video` FROM lesson WHERE chapter_id = ?";
        $lesson_stmt = $this->conn->prepare($lesson_q);

        $cid = intval($course_res['cid']);
        $chapter_stmt->bindParam(1, $cid, PDO::PARAM_INT);
        $chapter_stmt->execute();
        $course_res['chapters'] = $chapter_stmt->fetchAll(PDO::FETCH_ASSOC);
        $course_res['period'] .= intval($course_res['period']) === 1 ? " Month" : " Months";
        // get lessons
        foreach($course_res['chapters'] as &$chapter){
            $ch_id = intval($chapter['chapter_id']);
            $lesson_stmt->bindParam(1, $ch_id, PDO::PARAM_INT);
            $lesson_stmt->execute();
            $chapter['lessons'] = $lesson_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}