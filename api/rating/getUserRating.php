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
http_response_code(200);
if(intval($rating_res['num']) === 0) {
    echo json_encode(array('message' => "Has Not Rated Before."));
} 
else {
    $rating_res['id'] = intval($rating_res['id']);
    $rating_res['rate'] = intval($rating_res['rate']);
    echo json_encode(array(
        'message' => "Already Rated.", 
        'result' => $rating_res
    ));
}