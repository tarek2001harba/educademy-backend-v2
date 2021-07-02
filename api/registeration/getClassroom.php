<?php
include_once '../../config/Database.php';
include_once '../../models/Course.php';
include_once '../../models/Registeration.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$course = new Course($db->getConnection());
$reg = new Registeration($db->getConnection());
$reg->student_id = $sid;
$courses_ids = $reg->getClassroom();
$courses = array();

foreach($courses_ids as $cid){
    $course->course_id = $cid[0];
    array_push($courses, $course->getCourse());
}

if(count($courses) > 0){
    http_response_code(200);
    echo json_encode($courses);
}
else{
    http_response_code(404);
    echo json_encode(array('message' => 'Could not get courses.'));
}