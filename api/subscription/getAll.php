<?php
include_once '../../config/Database.php';
include_once '../../models/Subscription.php';
include_once '../../models/Purchase.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
extract($data);
$db = new Database();
$sub = new Subscription($db->getConnection());
$pur = new Purchase($db->getConnection());
$plans = $sub->getAll();
$existed_plan = null;
$no_exist = 0;
if(isset($sid)){
    $pur->student_id = $sid;
    $existed_plan = $pur->getStudentSub();
    foreach($plans as &$plan){
        if(intval($plan['id']) === $existed_plan){
            $plan['purchased'] = true;
        }
        else{
            $no_exist++;
            $plan['purchased'] = false;
        }
    }
} else{
    foreach($plans as &$plan){
        $plan['purchased'] = -1;
    }
}
if($no_exist === 3){
    foreach($plans as &$plan){
        $plan['purchased'] = -1;
    }
}
echo json_encode($plans);