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
$rating_res = $rating->getRating();
if($rating_res['num'] === 0) {
    http_response_code(404);
    echo json_encode(array('message' => "Has Not Rated Before.", "rated" => false));
} 
else {
    http_response_code(200);
    echo json_encode(array(
        'message' => "Already Rated.", 
        'rid' => intval($rating_res['id']),
        'rate' => intval($rating_res['rate']),
        'comment' => $rating_res['comment'],
        'rated' => true
    ));
}