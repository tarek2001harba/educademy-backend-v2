<?php
include_once '../../config/Database.php';
include_once '../../models/Rating.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$rating = new Rating($db->getConnection());
$rating->student_id = $sid;
$rating->course_id = $cid;
$rating->rating_comment = $comment;
$rating->rating_rate = $rate;
if($rating->create()){
    http_response_code(200);
    echo json_encode(array('message' => "Successful Rating. rate".$rate));
} else{
    http_response_code(503);
    echo json_encode(array('message' => "Unable to rate this course."));
}