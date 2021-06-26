<?php
include_once '../../config/Database.php';
include_once '../../models/User.php';
include_once '../../models/Student.php';
include_once '../../models/Teacher.php';
include_once '../../functions/checks.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// adding user endpoint: https://localhost/educademy/api/user/add.php?fname=&lname=&gender=&bdate=&country=&spec=&about=&type=&phone=&email=&password=
$db = new Database();
// the data sent from the user as JSON format
$data = json_decode(file_get_contents("php://input"), true);
extract($data);
if($type === "Teacher"){
    $teacher = new Teacher($db->getConnection());
    $teacher->fname = $fname;
    $teacher->lname = $lname;
    $teacher->gender = $gender === "Male" ? 1 : 2; // gender_id
    $teacher->bdate = $bdate;
    $teacher->country = $country;
    $teacher->spec = $spec;
    $teacher->about = $about;
    $teacher->type = $type;
    $teacher->phone = $phone;
    $teacher->email = $email;
    $teacher->password = password_hash($password, PASSWORD_DEFAULT);
    // tries to create an account
    check_create($teacher->create(), $teacher, "Account");
}
else{
    $student = new Student($db->getConnection());
    $student->fname = $fname;
    $student->lname = $lname;
    $student->gender = $gender === "Male" ? 1 : 2; // gender_id
    $student->bdate = $bdate;
    $student->country = $country;
    $student->spec = $spec;
    $student->about = $about;
    $student->type = $type;
    $student->phone = $phone;
    $student->email = $email;
    $student->password = password_hash($password, PASSWORD_DEFAULT);
    // tries to create an account
    check_create($student->create(), $student, "Account");
}