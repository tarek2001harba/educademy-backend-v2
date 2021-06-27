<?php
include_once '../../config/Database.php';
include_once '../../models/Course.php';
include_once '../../models/Chapter.php';
include_once '../../models/Lesson.php';
include_once '../../functions/checks.php';

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

// setting course props
$course->course_teacher = $teacher;
$course->course_title = $title;
$course->course_desc = $desc;
$course->course_lang = $lang;
$course->course_teacher = $teacher;
$course->course_level = $level;
$course->course_ctg = $ctg;
$course->course_period = $period;
$course->course_thumb = $thumb;

// creating course
$course->create();

// creates all chapters sent from request
for($i = 0; $i < $chapters_arr->count(); $i++){
    $chapter->course_id = $course->course_id;
    $chapter->chapter_title = $chapters_arr[$i]["title"];
    $chapter->chapter_desc = $chapters_arr[$i]["desc"];
    $chapter->create();
}

// creates all lessons sent from request
for($i = 0; $i < $lessons_arr->count(); $i++){
    $chp = $lessons_arr[$i]["chapter"];
    $lesson->chapter_id = $chapter->findCID($course->course_id, $chp);
    $lesson->lesson_title = $lessons_arr[$i]["title"];
    $lesson->lesson_content = $lessons_arr[$i]["content"];
    $lesson->lesson_resource = $lessons_arr[$i]["res"];
    if($i === $lessons_arr->count()-1){
        check_create_course($lesson->create());
        break;
    }
    $lesson->create();
}