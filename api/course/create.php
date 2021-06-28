<?php
include_once '../../config/Database.php';
include_once '../../models/Course.php';
include_once '../../models/Chapter.php';
include_once '../../models/Lesson.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$chapters_arr = $chapters;
$lessons_arr = $lessons;
$course = new Course($db->getConnection());
$chapter = new Chapter($db->getConnection());
$lesson = new Lesson($db->getConnection());
// create check vars :boolean
$course_created = null;
$chapter_created = null;
$lesson_created = null;
// setting course props
$course->course_teacher = $teacher;
$course->course_title = $title;
$course->course_desc = $description;
$course->course_lang = $language;
$course->course_level = $level;
$course->course_ctg = $category;
$course->course_period = $period;
$course->course_thumb = $thumb;

// creating course
$course_created = $course->create();

// creates all chapters sent from request
if($course_created){
    $chapter->course_id = $course->course_id;
    for($i = 0; $i < count($chapters_arr); $i++){
        if($chapter_created === false){
            break;
        }
        $chapter->chapter_title = $chapters_arr[$i]["title"];
        $chapter->chapter_desc = $chapters_arr[$i]["description"];
        $chapter_created = $chapter->create();
    }
    // creates all lessons sent from request
    for($i = 0; $i < count($lessons_arr); $i++){
        if($lesson_created === false){
            break;
        }
        $chp = $lessons_arr[$i]["chapter"];
        $lesson->chapter_id = $chapter->findCID($chp, $course->course_id);
        $lesson->lesson_title = $lessons_arr[$i]["title"];
        $lesson->lesson_content = $lessons_arr[$i]["content"];
        $lesson->lesson_resource = $lessons_arr[$i]["resource"];
        $lesson->lesson_video = $lessons_arr[$i]["video"];
        $lesson_created = $lesson->create();
    }
    http_response_code(201);
    echo json_encode(array("message" =>  "Course Submited Successfully."));
}
else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to Submit Course."));
}